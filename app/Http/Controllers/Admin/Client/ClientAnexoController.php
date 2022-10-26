<?php

namespace App\Http\Controllers\Admin\Client;

use App\Http\Controllers\Controller;
use App\Models\AgendaEnvio;
use App\Models\AgendaPessoa;
use App\Models\AnexoCliente;
use App\Models\BoletoImpresso;
use App\Models\Contas;
use App\Models\Empresa;
use Carbon\Carbon;
use Eduardokum\LaravelBoleto\Contracts\Boleto\Boleto;
use Eduardokum\LaravelBoleto\Pessoa;
use Helper;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;

class ClientAnexoController extends Controller
{
    protected static $pagador;
    protected static $beneficiario;

    public function __construct(
        Request $request,
        Empresa $empresa,
        AgendaEnvio $envio,

        Contas $contas,
        BoletoImpresso $boleto
    ) {
        $this->empresa  = $empresa;
        $this->request = $request;

        $this->envio = $envio;
        $this->tickets = $contas;
        $this->boleto = $boleto;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Dados para sua Empresa';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        if ($this->user->cd_pessoa == '') {
            return Redirect::route('admin.dashboard')->with(['warning' => 'Usuario não está associado a nenhum pessoa, favor entrar em contato com o suporte tecnico!']);
        }

        return view('admin.clientes.index', compact('title_page', 'user_auth', 'uri'));
    }
    public function getTickesPendents()
    {
        $empresa = Empresa::where('cd_empresa', $this->request->emp)->firstOrFail();
        $data = $this->tickets->TicketsPendentsClient($empresa);

        return DataTables::of($data)
            ->addColumn('action', function ($d) {
                if ($d->CD_FORMAPAGTO == "DD") {
                    return  '<button id="btn-fidic" class="btn btn-xs btn-default">Fidic</button>';
                } else {
                    // return   '<button class="btn btn-xs btn-danger" id="btnDoc" data-documento="' . $d->NR_DOCUMENTO . '">Imprimir</button>';
                    return   '<a href=' . route("client-save-tickets", ["id" => Crypt::encryptString($d->NR_DOCUMENTO)]) . ' class="btn btn-xs btn-danger" target="_blank">Imprimir</a>';
                }
            })
            ->addColumn('valor_nf', function ($d) {
                return (float)$d->VALOR;
            })
            // <button class="btn btn-xs btn-success" id="btnNF" data-documento="' . $d->DOCUMENTO . '">NFs-e</button>
            ->setRowClass(function ($d) {
                $today = Carbon::now()->format('Y-m-d');
                $vencimento = Carbon::createFromFormat('Y-m-d', $d->DT_VENCIMENTO)->format('Y-m-d');
                if ($vencimento < $today) {
                    return 'bg-yellow';
                }
            })
            ->make(true);
    }
    public function saveTickets()
    {
        if (!$this->request->has('id')) {
            return abort(404);
        }
        try {
            $nr_doc = Crypt::decryptString($this->request->id);
        } catch (\Throwable $th) {
            return abort(404);
        }
        $dados = $this->boleto->Boleto($nr_doc);

        if ($this->user->cd_pessoa <> $dados[0]->CD_PESSOA) {
            return abort(404);
        }

        $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa(
            [
                'nome'      => $dados[0]->NMBENF,
                'endereco'  => $dados[0]->ENDBENF,
                'cep'       => $dados[0]->CEPBENF,
                'uf'        => $dados[0]->ESTBENF,
                'cidade'    => $dados[0]->MUNBENF,
                'documento' => $dados[0]->NR_CNPJCPFCEDENTE,
            ]
        );

        $pagador = new \Eduardokum\LaravelBoleto\Pessoa(
            [
                'nome'      => $dados[0]->NM_SACADO,
                'endereco'  => $dados[0]->DS_ENDERECOSACADO,
                'bairro'    => $dados[0]->DS_BAIRRO,
                'cep'       => $dados[0]->NR_CEP,
                'uf'        => $dados[0]->SG_ESTADO,
                'cidade'    => $dados[0]->DS_MUNICIPIO,
                'documento' => $dados[0]->NR_CNPJCPFSACADO,
            ]
        );


        if ($dados[0]->CD_BANCO == 33) {
            $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Santander(
                $this->InfoTicket($dados, $pagador, $beneficiario)
            );
        } elseif ($dados[0]->CD_BANCO == 341) {
            $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Itau(
                $this->InfoTicket($dados, $pagador, $beneficiario)
            );
        } elseif ($dados[0]->CD_BANCO == 104) {
            $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Caixa(
                $this->InfoTicket($dados, $pagador, $beneficiario)
            );
        } elseif ($dados[0]->CD_BANCO == 1) {
            $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Bb(
                $this->InfoTicket($dados, $pagador, $beneficiario)
            );
        } elseif ($dados[0]->CD_BANCO == 237) {
            $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Bradesco(
                $this->InfoTicket($dados, $pagador, $beneficiario)
            );
        } else {
            return "Outro";
        }
        // Gerar em HTML
        $html = new \Eduardokum\LaravelBoleto\Boleto\Render\Html();
        $html->addBoleto($boleto);

        // Para mostrar a janela de impressão no load da página
        $html->showPrint();

        return $html->gerarBoleto();
    }
    public function InfoTicket($dados, $pagador, $beneficiario)
    {
        return [
            'logo'                   => realpath(__DIR__ . '/../../../../../public/img/logo-ivo.png'),
            'dataVencimento'         => new \Carbon\Carbon($dados[0]->DT_VENC),
            'valor'                  => $dados[0]->VL_DOCUMENTO,
            'multa'                  => false,
            'juros'                  => false,
            'numero'                 => $dados[0]->NR_NOSSONUMERO,
            'numeroDocumento'        => $dados[0]->NR_DOC,
            'pagador'                => $pagador,
            'beneficiario'           => $beneficiario,
            'carteira'               => $dados[0]->NR_CARTEIRA,
            'agencia'                => $dados[0]->CD_AGENCIA,
            'conta'                  => $dados[0]->CD_CONTACOR,
            'convenio'               => $dados[0]->CD_CONVENIO,
            'codigoCliente'          => $dados[0]->CD_CODIGOCEDENTE,
            'descricaoDemonstrativo' => [$dados[0]->DS_INSTRUCAO],
            'instrucoes'             => [$dados[0]->DS_INSTRUCAO],
            'aceite'                 => 'S',
            'especieDoc'             => 'DM',
        ];
    }
    public function viewPdfTicket($cliente, $path, $anexo)
    {
        $path_ =  $path . '/' . $anexo;
        if ($this->user->cd_pessoa <> $cliente) {
            return abort(404);
        }
        $exists = Storage::disk('public')->exists($path_);
        if ($exists) {
            //get content of image
            $content = Storage::get($path_);
            //get mime type of image
            $mime = Storage::mimeType($path_);
            //prepare response with image content and response code
            $response = Response::make($content, 200);
            //set header
            $response->header("Content-Type", $mime);
            // return response
            return $response;
        } else {
            return abort(404);
        }
    }
    public function InvoiceClient()
    {
        $empresa = Empresa::where('cd_empresa', $this->request->emp)->firstOrFail();
        if ($this->request->has('dt_ini')) {
            if ($this->request->dt_ini == 0) {
                $dt_ini = '01-01-2022';
                $dt_fim = Config::get('constants.options.today');
            } else {
                $this->request->validate(
                    [
                        'dt_ini' => 'required|date',
                        'dt_fim' => 'required|date'
                    ],
                    [
                        'dt_ini.required' => 'Data inicial ser preenchida!',
                        'dt_ini.date' => 'Deve ser uma data valida!',
                        'dt_ini.required' => 'Data final ser preenchida!',
                        'dt_ini.date' => 'Deve ser uma data valida!',
                    ]
                );
                $dt_ini = str_replace("/", "-", $this->request->dt_ini);
                $dt_fim = str_replace("/", "-", $this->request->dt_fim);
            }
        }
        $data = $this->tickets->InvoiceClient($empresa, $dt_ini, $dt_fim);
        return DataTables::of($data)
            ->addColumn('action', function ($d) {
                return '<a href="' . env('URL_PREF_CAMP') . '?cpfCnpjPrestador=' . Helper::RemoveSpecialChar($d->NR_CNPJ_EMI) . '&numeroNFSe=' . $d->NR_NOTASERVICO . '&codigoAutenticidade=' . $d->CD_AUTENTICACAO . '&dataEmissao=' . $d->DS_DTEMISSAO . '" class="btn btn-xs btn-primary" target="_blank">NFs-e</button>';
            })
            ->addColumn('valor_nf', function ($d) {
                return (float)$d->VL_NF;
            })
            ->make(true);
    }
}
