<?php

namespace App\Http\Controllers\Admin\Digisac;

use App\Http\Controllers\Controller;
use App\Models\Boleto;
use App\Models\BoletoImpresso;
use App\Models\Nota;
use App\Models\Pessoa;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Digisac;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class DigiSacController extends Controller
{
    public $pessoa, $nota, $user, $request, $boleto;

    public function __construct(
        Request $request,
        Pessoa $pessoa,
        Nota $nota,
        Boleto $boleto
    ) {
        $this->pessoa = $pessoa;
        $this->nota = $nota;
        $this->request = $request;
        $this->boleto = $boleto;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function notafiscal()
    {
        $oauth = Digisac::OAuthToken();

        // $nota = $this->nota->NotasEmitidasResumo(0, 0);

        // $this->nota->StoreNota($nota);

        $notas_para_enviar = $this->nota->listNotaSend();

        foreach ($notas_para_enviar as $index => $nota) {

            $search_send = $this->nota->NotasEmitidas($nota['NR_LANCAMENTO'], $nota['CD_EMPRESA']);

            if (empty($search_send[0]['CD_AUTENTICACAO'])) {
                continue;
            };
            if (empty($search_send[0]['NR_CELULAR'])) {
                $this->nota->UpdateNotaSend($search_send[0]['NR_LANCAMENTO'], 'N');
                continue;
            };
            if ($search_send[0]['ST_NOTA'] == 'C') {
                $this->nota->UpdateNotaSend($search_send[0]['NR_LANCAMENTO'], 'C');
                continue;
            };

            $envio = Digisac::SendMessage($oauth, $search_send[0], null);

            if (!empty($envio->error)) {
                $this->nota->UpdateNotaSend($search_send[0]['NR_LANCAMENTO'], 'B');
                continue;
            }

            $this->CleanDiretory('notas');

            $pdf = self::CreatePdfNota($search_send, $index, $oauth);

            // return $pdf->inline('nota_fiscal.pdf'); //Exibe o pdf sem fazer o downlaod.       

            $filePath = storage_path('app/public/notas/' . $search_send[0]['NR_DOCUMENTO'] . '.pdf');

            $pdf->save($filePath);

            // Lê o conteúdo do arquivo PDF
            $pdfContent = file_get_contents($filePath);

            // Converte o conteúdo do PDF para base64
            $base64Pdf = base64_encode($pdfContent);

            // return $pdf->download('notafiscal.pdf');

            $envio = Digisac::SendMessage($oauth, $search_send[0], $base64Pdf);

            if ($envio->sent == true) {
                $this->nota->UpdateNotaSend($search_send[0]['NR_LANCAMENTO'], 'E');
            } elseif (!empty($envio->error)) {
                $this->nota->UpdateNotaSend($search_send[0]['NR_LANCAMENTO'], 'B');
                continue;
            }

            // lista e salva os dados de boleto no mysql
            self::listAndStoreBoleto();

            $boletosSend = $this->boleto->listBoletoSend($search_send[0]);

            if (!empty($boletosSend[0]['STATUS']) && $boletosSend[0]['STATUS'] == "A") {
                $this->BoletoLoop($boletosSend, $oauth, true);
            } else {
                echo $search_send[0]['NR_DOCUMENTO'] . " Sem Boleto.</br>";
            }

            echo $search_send[0]['NR_DOCUMENTO'] . " Nota Processado.</br>";
        }
    }
    public function AgruparNota($nota)
    {
        return array_values(array_reduce($nota, function ($carry, $item) {
            $nrLancamento = $item['NR_LANCAMENTO'];

            if (!isset($carry[$nrLancamento])) {
                $carry[$nrLancamento] = [
                    "CD_EMPRESA" => $item["CD_EMPRESA"],
                    "NR_LANCAMENTO" => $item["NR_LANCAMENTO"],
                    "TP_NOTA" => $item["TP_NOTA"],
                    "CD_SERIE" => $item["CD_SERIE"],
                    "DS_DTEMISSAO" => $item["DS_DTEMISSAO"],
                    "HR_NOTA" => $item["HR_NOTA"],
                    "NM_EMPRESA" => $item["NM_EMPRESA"],
                    "NM_FANTASIA" => $item["NM_FANTASIA"],
                    "NR_CNPJEMPRESA" => $item["NR_CNPJEMPRESA"],
                    "NR_INSCESTEMPRESA" => $item["NR_INSCESTEMPRESA"],
                    "NR_INSCMUNEMPRESA" => $item["NR_INSCMUNEMPRESA"],
                    "DS_SITEEMPRESA" => $item["DS_SITEEMPRESA"],
                    "DS_EMAILEMPRESA" => $item["DS_EMAILEMPRESA"],
                    "DS_ENDEMPRESA" => $item["DS_ENDEMPRESA"],
                    "NR_ENDEMPRESA" => $item["NR_ENDEMPRESA"],
                    "NR_CEPEMPRESA" => $item["NR_CEPEMPRESA"],
                    "DS_BAIRROEMPRESA" => $item["DS_BAIRROEMPRESA"],
                    "DS_COMPEMPRESA" => $item["DS_COMPEMPRESA"],
                    "DS_MUNICIPIOEMP" => $item["DS_MUNICIPIOEMP"],
                    "NR_FONEEMPRESA" => $item["NR_FONEEMPRESA"],
                    "NR_FAXEMPRESA" => $item["NR_FAXEMPRESA"],
                    "DS_LOGOTIPO" => $item["DS_LOGOTIPO"],
                    "NM_USUARIO" => $item["NM_USUARIO"],
                    "DS_ENDERECOEMP" => $item["DS_ENDERECOEMP"],
                    "DS_ENDERECOEMPRESA" => $item["DS_ENDERECOEMPRESA"],
                    "DS_MUNEMPRESA" => $item["DS_MUNEMPRESA"],
                    "NR_CNPJINSCEST" => $item["NR_CNPJINSCEST"],
                    "NR_TELEFONEEMPRESA" => $item["NR_TELEFONEEMPRESA"],
                    "NR_NOTA" => $item["NR_NOTA"],
                    "NR_NOTAPREFA" => $item["NR_NOTAPREFA"],
                    "DT_EMISSAONOTA" => $item["DT_EMISSAONOTA"],
                    "NR_NOTASERVICO" => $item["NR_NOTASERVICO"],
                    "CD_AUTENTICACAO" => $item["CD_AUTENTICACAO"],
                    "DT_EMISSAORPS" => $item["DT_EMISSAORPS"],
                    "DS_NOTASERIEDATA" => $item["DS_NOTASERIEDATA"],
                    "NR_LOTERPS" => $item["NR_LOTERPS"],
                    "NR_RPS" => $item["NR_RPS"],
                    "NR_DOCUMENTO" => $item["NR_DOCUMENTO"],
                    "HR_DOCUMENTO" => $item["HR_DOCUMENTO"],
                    "O_DS_CONDPAGTO" => $item["O_DS_CONDPAGTO"],
                    "DS_OBSNOTA" => $item["DS_OBSNOTA"],
                    "DS_OBSFISCAL" => $item["DS_OBSFISCAL"],
                    "NM_VENDEDOR" => $item["NM_VENDEDOR"],
                    "CD_CONDPAGTO" => $item["CD_CONDPAGTO"],
                    "DS_CONDPAGTO" => $item["DS_CONDPAGTO"],
                    "CD_FORMAPAGTO" => $item["CD_FORMAPAGTO"],
                    "DS_FORMAPAGTO" => $item["DS_FORMAPAGTO"],
                    "CD_PESSOA" => $item["CD_PESSOA"],
                    "NM_PESSOA" => $item["NM_PESSOA"],
                    "NR_CNPJCPF" => $item["NR_CNPJCPF"],
                    "DS_EMAIL" => $item["DS_EMAIL"],
                    "NM_FANTASIAPESSOA" => $item["NM_FANTASIAPESSOA"],
                    "NR_ENDPESSOA" => $item["NR_ENDPESSOA"],
                    "NR_INSCMUN" => $item["NR_INSCMUN"],
                    "NR_CEPPESSOA" => $item["NR_CEPPESSOA"],
                    "DS_BAIRROPESSOA" => $item["DS_BAIRROPESSOA"],
                    "DS_COMPPESSOA" => $item["DS_COMPPESSOA"],
                    "DS_MUNICIPIO" => $item["DS_MUNICIPIO"],
                    "DS_MUNPESSOA" => $item["DS_MUNPESSOA"],
                    "SG_ESTADO" => $item["SG_ESTADO"],
                    "NR_FONE" => $item["NR_FONE"],
                    "NR_FAX" => $item["NR_FAX"],
                    "DS_CONTATO" => $item["DS_CONTATO"],
                    "NR_CELULAR" => Helper::RemoveSpecialChar($item["NR_CELULAR"]),
                    "DS_ENDERECOPESSOA" => $item["DS_ENDERECOPESSOA"],
                    "DS_ENDPESSOA" => $item["DS_ENDPESSOA"],
                    "NR_INSCESTPESSOA" => $item["NR_INSCESTPESSOA"],
                    "DS_ENDCOMPLETOPESSOA" => $item["DS_ENDCOMPLETOPESSOA"],
                    "TOT_VL_ITENS" => $item["TOT_VL_ITENS"],
                    "TOT_QT_ITENS" => $item["TOT_QT_ITENS"],
                    "TOT_QT_PNEUS" => $item["TOT_QT_PNEUS"],
                    "TOT_QT_PRODUZIDOS" => $item["TOT_QT_PRODUZIDOS"],
                    "TOT_QT_RECUSADOS" => $item["TOT_QT_RECUSADOS"],
                    "DS_OBSERVACAOTPO001" => $item["DS_OBSERVACAOTPO001"],
                    "VL_ISSQN" => $item["VL_ISSQN"],
                    "VL_ISSQN_RETIDO" => $item["VL_ISSQN_RETIDO"],
                    "VL_CONTABIL" => $item["VL_CONTABIL"],
                    "ITEMS" => []
                ];
            }

            $carry[$nrLancamento]['ITEMS'][] = [
                "O_CD_ITEM" => $item["O_CD_ITEM"],
                "O_DS_ITEM" => $item["O_DS_ITEM"],
                "O_QTDE" => $item["O_QTDE"],
                "CD_SUBGRUPO" => $item["CD_SUBGRUPO"],
                "O_VL_UNITARIO" => $item["O_VL_UNITARIO"],
                "O_VL_TOTAL" => $item["O_VL_TOTAL"],
                "O_QT_DESCONTADA" => $item["O_QT_DESCONTADA"],
                "O_NR_SERIE" => $item["O_NR_SERIE"],
                "O_NR_DOT" => $item["O_NR_DOT"],
                "O_NR_FOGO" => $item["O_NR_FOGO"],
                "O_DS_MODELO" => $item["O_DS_MODELO"],
                "O_DS_MARCA" => $item["O_DS_MARCA"],
                "O_DS_MEDIDAPNEU" => $item["O_DS_MEDIDAPNEU"],
                "O_DS_DESENHO" => $item["O_DS_DESENHO"],
                "O_IDORDEMPRODUCAORECAP" => $item["O_IDORDEMPRODUCAORECAP"],
                "O_ORDEM" => $item["O_ORDEM"]
            ];

            return $carry;
        }, []));
    }
    public function ListEnvioNotaDigisac()
    {
        $data = $this->nota->listNotaSendAll();

        return DataTables::of($data)
            ->addColumn('actions', function ($data) {
                return '<button class="btn btn-xs btn-primary EditSend" data-id=" ' . $data->id . ' ">Reenviar</button>';
                // <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Excluir</button>
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    public function reenvioNotafiscal()
    {
        $id = $this->request->id;
        $this->nota->UpdateNotaReenvia($id);

        return response()->json(['success' => 'Nova tentativa de disparo, aguarde processamento!']);
    }
    public function Boleto()
    {
        $oauth = Digisac::OAuthToken();

        self::listAndStoreBoleto();

        $boletosSend = $this->boleto->listBoletoSend(null);

        $this->BoletoLoop($boletosSend, $oauth, false);
    }
    public function BoletoLoop($boletosSend, $oauth, $status)
    {
        foreach ($boletosSend as $boleto) {

            $boletos = $this->boleto->Boleto($boleto->NR_LANCAMENTO, $boleto->CD_EMPRESA);

            //Verifica se existe numero de celular caso não existe vai para o proximo
            if (empty($boletos[0]['NR_CELULAR'])) {
                $this->boleto->UpdateBoletoSend($boleto, 'N');
                continue;
            };

            if ($status === false) {
                $envio = Digisac::SendMessage($oauth, $boleto, null);
                //verificar se o cliente possui Whatsapp antes de continuar, caso não pula para o proximo
                if (!empty($envio->error)) {
                    $this->boleto->UpdateBoletoSend($boleto, 'B');
                    continue;
                }
            }

            $htmlArray = [];

            foreach ($boletos as $boleto) {

                $codigo_barras = $this->getImagemCodigoDeBarras($boleto['DS_CODIGOBARRA']);

                $view = view('admin.nota_boleto.boleto', compact('codigo_barras', 'boleto'));

                $htmlArray[] = $view->render();
            }

            $html = implode('<div style="page-break-after: always;"></div>', $htmlArray);

            // Configurando o Snappy
            $options = [
                'page-size' => 'A4',
                'no-stop-slow-scripts' => true,
                'enable-javascript' => true,
                'lowquality' => true,
                'encoding' => 'UTF-8'
            ];

            $pdf = SnappyPdf::loadHTML($html)->setOptions($options);

            $this->CleanDiretory('boleto');

            // $pdf->inline('nota_fiscal.pdf'); //Exibe o pdf sem fazer o downlaod.

            $filePath = storage_path('app/public/boleto/boleto' . $boleto['NR_DOCUMENTO'] . '.pdf');

            $pdf->save($filePath);


            // Lê o conteúdo do arquivo PDF
            $pdfContent = file_get_contents($filePath);

            // Converte o conteúdo do PDF para base64
            $base64Pdf = base64_encode($pdfContent);

            // return $pdf->download('notafiscal.pdf');

            $envio = Digisac::SendMessage($oauth, $boleto, $base64Pdf);

            if (!empty($envio->sent)) {
                $this->boleto->UpdateBoletoSend($boleto, 'E');
            }
            echo $boleto['NR_DOCUMENTO'] . " Boleto Processado / ";
        }
    }
    public function getImagemCodigoDeBarras($codigo_barras)
    {
        $codigo_barras = (strlen($codigo_barras) % 2 != 0 ? '0' : '') . $codigo_barras;
        $barcodes = ['00110', '10001', '01001', '11000', '00101', '10100', '01100', '00011', '10010', '01010'];
        for ($f1 = 9; $f1 >= 0; $f1--) {
            for ($f2 = 9; $f2 >= 0; $f2--) {
                $f = ($f1 * 10) + $f2;
                $texto = "";
                for ($i = 1; $i < 6; $i++) {
                    $texto .= substr($barcodes[$f1], ($i - 1), 1) . substr($barcodes[$f2], ($i - 1), 1);
                }
                $barcodes[$f] = $texto;
            }
        }

        // Guarda inicial
        $retorno = '<div class="barcode">' .
            '<div class="black thin"></div>' .
            '<div class="white thin"></div>' .
            '<div class="black thin"></div>' .
            '<div class="white thin"></div>';

        // Draw dos dados
        while (strlen($codigo_barras) > 0) {
            $i = round(substr($codigo_barras, 0, 2));
            $codigo_barras = substr($codigo_barras, strlen($codigo_barras) - (strlen($codigo_barras) - 2), strlen($codigo_barras) - 2);
            $f = $barcodes[$i];
            for ($i = 1; $i < 11; $i += 2) {
                if (substr($f, ($i - 1), 1) == "0") {
                    $f1 = 'thin';
                } else {
                    $f1 = 'large';
                }
                $retorno .= "<div class='black {$f1}'></div>";
                if (substr($f, $i, 1) == "0") {
                    $f2 = 'thin';
                } else {
                    $f2 = 'large';
                }
                $retorno .= "<div class='white {$f2}'></div>";
            }
        }

        // Final
        return $retorno . '<div class="black large"></div>' .
            '<div class="white thin"></div>' .
            '<div class="black thin"></div>' .
            '</div>';
    }
    public function CleanDiretory($diretory)
    {
        $diretory = storage_path('app/public/' . $diretory . '');

        if (File::exists($diretory)) {
            File::cleanDirectory($diretory);
        }
    }
    private function CreatePdfNota($search_send, $index, $oauth)
    {
        $nota = $this->AgruparNota($search_send);

        $view = View::make('admin.nota_boleto.notafiscal', compact(
            'nota',
            'index'
        ));

        $html = $view->render();

        // Configurando o Snappy
        $options = [
            'page-size' => 'A4',
            'no-stop-slow-scripts' => true,
            'enable-javascript' => true,
            'encoding' => 'UTF-8'

        ];

        return SnappyPdf::loadHTML($html)->setOptions($options);
    }
    private function listAndStoreBoleto()
    {
        $boletosStore = $this->boleto->BoletoResumo();

        $this->boleto->storeData($boletosStore);
    }
}
