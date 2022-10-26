<?php

namespace App\Http\Controllers\Admin\Producao;

use App\Http\Controllers\Controller;
use App\Models\ApiNewAge;
use App\Models\Empresa;
use App\Models\LogApiNewAge;
use App\Models\MedidaPneu;
use App\Models\ModeloPneu;
use Carbon\Carbon;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\Facades\DataTables;

class ApiNewAgeController extends Controller
{
    protected $soapWrapper;

    public function __construct(
        ApiNewAge $api,
        Request $request,
        Empresa $empresa,
        LogApiNewAge $logNewAge,
        ModeloPneu $modelo,
        MedidaPneu $medida
    ) {
        $this->request = $request;
        $this->empresa = $empresa;
        $this->apiNewAge = $api;
        $this->logNewAge = $logNewAge;
        $this->modelopneu = $modelo;
        $this->medida = $medida;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        $title_page   = 'Exportação Automatica';
        $modelo = $this->modelopneu->list();
        $medida = $this->medida->list();
        $ultima_transmissao = $this->apiNewAge->UltimaTransmissao($this->user->empresa);
        if (count($ultima_transmissao) == 0) {
            $dt_inicial = Config::get('constants.options.dt1_h_m_days');
        } else {
            $dt_inicial = $ultima_transmissao[0]->ULTIMA_TRASNMISSAO;
        }
        $dt_final = date('m-d-Y H:i');
        $saveOrdens = $this->searchPneusJunsoft($this->user->empresa, $dt_inicial, $dt_final);

        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        $empresas = $this->empresa->EmpresaFiscal(Helper::VerifyRegion($this->user->conexao));
        return view('admin.producao.garantia-bgw', compact(
            'title_page',
            'user_auth',
            'uri',
            'empresas',
            'modelo',
            'medida'
        ));
    }
    public function GetPneusEnviarBandag()
    {
        $data = $this->apiNewAge->pneusEnviar($this->request->exportado, $this->user->empresa);
        return DataTables::of($data)
            ->addColumn('Actions', function ($data) {
                if ($data->EXPORTADO == 'N') {
                    return '<button type="button" class="btn btn-warning btn-sm" id="getEdit" data-modelo="' . $data->MODELO . '" data-id="' . $data->id . '" data-medida="' . $data->COD_I_MED . '">Editar</button>';
                } elseif ($data->EXPORTADO == 'C') {
                    return '<button type="button" class="btn btn-danger btn-sm" data-ordem="' . $data->ORD_NUMERO . '" id="getFalhas">Falhas Envio</button>';
                } else {
                    return '<i class="fa fa-fw fa-check-square-o"></i>';
                }
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }
    public function searchPneusJunsoft($empresa, $dt_inicial, $dt_final)
    {
        $pneusJunsoft =  $this->apiNewAge->pneusBGW($empresa, $dt_inicial, $dt_final);
        foreach ($pneusJunsoft as $p) {
            $p->ORD_HOREMI = $this->InsertHora($p->ORD_HOREMI);
            $p->MEDIDA = $this->RemovePCasa($p->MEDIDA);
            $p->COD_I_MED = $this->RemovePCasa($p->COD_I_MED);
        }
        return $this->apiNewAge->store($pneusJunsoft);
    }
    public function ImportPneus()
    {
        $empresa = $this->request['empresa'];
        $dt_inicial = $this->request['inicio_data'];
        $dt_final = $this->request['fim_data'];
        return $this->searchPneusJunsoft($empresa, $dt_inicial, $dt_final);
    }
    public function RemoveSpecialChar($str)
    {
        return preg_replace('/[0-9\@\.\;\&]+/', '', $str);
    }
    public function InsertHora($hora)
    {
        // Hora abaixo e falsa somente porque as ordens feitas manuais não salvam hora no banco junsoft
        return empty($hora) ? "07:16:32" : $hora;
    }
    public function RemovePCasa($str)
    {
        return preg_replace('/PCASA /', '', $str);
    }
    public function callXmlProcess()
    {
        if ($this->user->empresa == 3) {
            $custumerid = env('CUSTUMERID_NEWAGE_SUL');
            $username = env('USERNAME_NEWAGE_SUL');
            $password = env('PASSWORD_NEWAGE_SUL');
            $cod_emp =  env('COD_I_EMP_SUL');
        } elseif ($this->user->empresa == 101) {
            $custumerid = env('CUSTUMERID_NEWAGE_PVAI');
            $username = env('USERNAME_NEWAGE_PVAI');
            $password = env('PASSWORD_NEWAGE_PVAI');
            $cod_emp =  env('COD_I_EMP_PVAI');
        } elseif ($this->user->empresa == 304) {
            $custumerid = env('CUSTUMERID_NEWAGE_ASSIS');
            $username = env('USERNAME_NEWAGE_ASSIS');
            $password = env('PASSWORD_NEWAGE_ASSIS');
            $cod_emp =  env('COD_I_EMP_ASSIS');
        } elseif ($this->user->empresa == 102) {
            $custumerid = env('CUSTUMERID_NEWAGE_DOU');
            $username = env('USERNAME_NEWAGE_DOU');
            $password = env('PASSWORD_NEWAGE_DOU');
            $cod_emp =  env('COD_I_EMP_DOU');
        } else {
            return "<div align='center'><h3>Empresa não existe vinculo com a bandag, contacte setor de TI!</h3></div>";
        }
        $pneus = $this->apiNewAge->pneusEnviar('N', $this->user->empresa);
        $qtd_reg = count($pneus);

        foreach ($pneus as $p) {
            $ordens[] = "&lt;REGISTRO ORD_NUMERO=\"$p->ORD_NUMERO\"&gt;
            &lt;ORD_CODBTS&gt;" . $cod_emp . "&lt;/ORD_CODBTS&gt;
            &lt;ORD_NUMERO&gt;$p->ORD_NUMERO&lt;/ORD_NUMERO&gt;
            &lt;ORD_DATEMI&gt;" . Carbon::createFromFormat('Y-m-d', $p->ORD_DATEMI)->format('d/m/Y') . "&lt;/ORD_DATEMI&gt;
            &lt;ORD_HOREMI&gt;" . $this->InsertHora($p->ORD_HOREMI) . "&lt;/ORD_HOREMI&gt;
            &lt;NUM_NF&gt;$p->NUM_NF&lt;/NUM_NF&gt;
            &lt;DATA_NF&gt;" . Carbon::createFromFormat('Y-m-d', $p->DATA_NF)->format('d/m/Y') . "&lt;/DATA_NF&gt;
            &lt;CLI_CPF&gt;$p->CLI_CPF&lt;/CLI_CPF&gt;
            &lt;CLI_NOME&gt; " . $this->RemoveSpecialChar($p->CLI_NOME) . "&lt;/CLI_NOME&gt;
            &lt;CLI_CEP&gt;$p->CLI_CEP&lt;/CLI_CEP&gt;
            &lt;CLI_TLOGRA&gt;&lt;/CLI_TLOGRA&gt;
            &lt;CLI_LOGRAD&gt;$p->CLI_LOGRAD&lt;/CLI_LOGRAD&gt;
            &lt;CLI_NUMERO&gt;$p->CLI_NUMERO&lt;/CLI_NUMERO&gt;
            &lt;CLI_COMPL&gt;" . $this->RemoveSpecialChar($p->CLI_COMPL) . "&lt;/CLI_COMPL&gt;
            &lt;CLI_BAIRRO&gt;$p->CLI_BAIRRO&lt;/CLI_BAIRRO&gt;
            &lt;CLI_CIDADE&gt;$p->CLI_CIDADE&lt;/CLI_CIDADE&gt;
            &lt;CLI_UF&gt;$p->CLI_UF&lt;/CLI_UF&gt;
            &lt;CLI_PAIS&gt;1058&lt;/CLI_PAIS&gt;
            &lt;CLI_CXPOST&gt;&lt;/CLI_CXPOST&gt;
            &lt;CLI_EMAIL&gt;$p->CLI_EMAIL&lt;/CLI_EMAIL&gt;
            &lt;CLI_DDD&gt;&lt;/CLI_DDD&gt;
            &lt;CLI_TEL1&gt;$p->CLI_TEL1&lt;/CLI_TEL1&gt;
            &lt;FAX&gt;&lt;/FAX&gt;
            &lt;POS_TIPO&gt;TP&lt;/POS_TIPO&gt;
            &lt;MEDIDA&gt;" . $this->RemovePCasa($p->MEDIDA) . "&lt;/MEDIDA&gt;
            &lt;CONSTRUCAO&gt;RADIAL&lt;/CONSTRUCAO&gt;
            &lt;BANDA&gt;$p->BANDA&lt;/BANDA&gt;
            &lt;MATRICULA&gt;$p->MATRICULA&lt;/MATRICULA&gt;
            &lt;NUM_FOGO&gt;$p->FOGO&lt;/NUM_FOGO&gt;
            &lt;DOT&gt;$p->DOT&lt;/DOT&gt;
            &lt;MARCA&gt;$p->MARCA&lt;/MARCA&gt;
            &lt;MODELO&gt;$p->MODELO&lt;/MODELO&gt;
            &lt;CICLOVIDA&gt;$p->CICLOVIDA&lt;/CICLOVIDA&gt;
            &lt;TIPO_GAR&gt;BGW&lt;/TIPO_GAR&gt;
            &lt;EXAME_INI&gt;1I&lt;/EXAME_INI&gt;
            &lt;EXAME_NDI&gt;&lt;/EXAME_NDI&gt;
            &lt;EXAME_FIM&gt;F1&lt;/EXAME_FIM&gt;
            &lt;CHV_COLETA&gt;$p->CHV_COLETA&lt;/CHV_COLETA&gt;
            &lt;PRECO&gt;$p->PRECO&lt;/PRECO&gt;
            &lt;SITUACAO&gt;005&lt;/SITUACAO&gt;
            &lt;COD_REPARO&gt;0&lt;/COD_REPARO&gt;
            &lt;COD_I_REPARO&gt;0&lt;/COD_I_REPARO&gt;
            &lt;QUADRANTE&gt;0&lt;/QUADRANTE&gt;
            &lt;COD_I_CICLO&gt;$p->COD_I_CICLO&lt;/COD_I_CICLO&gt;
            &lt;COD_I_CONS&gt;RADIAL&lt;/COD_I_CONS&gt;
            &lt;COD_I_EMP&gt;" . $cod_emp . "&lt;/COD_I_EMP&gt;
            &lt;COD_I_MARCA&gt;$p->COD_I_MARCA&lt;/COD_I_MARCA&gt;
            &lt;COD_I_MOD&gt;$p->MODELO&lt;/COD_I_MOD&gt;
            &lt;COD_I_MED&gt;" . $this->RemovePCasa($p->COD_I_MED) . "&lt;/COD_I_MED&gt;
            &lt;COD_I_BANDA&gt;$p->COD_I_BANDA&lt;/COD_I_BANDA&gt;
            &lt;COD_I_TPGAR&gt;BGW&lt;/COD_I_TPGAR&gt;
            &lt;COD_I_EX_I&gt;1I&lt;/COD_I_EX_I&gt;
            &lt;COD_I_EX_NDI&gt;NDI&lt;/COD_I_EX_NDI&gt;
            &lt;COD_I_EX_F&gt;1F&lt;/COD_I_EX_F&gt;
        &lt;/REGISTRO&gt;";
        }

        if (empty($ordens)) {
            return "<div align='center'><h3>Não possui Pneus a processar!</h3></div>";
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://186.202.136.122/NewAgeWebServiceSetup/EASWebService.asmx",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">\n  
                                    <soap:Body>    
                                        <callXmlProcess xmlns=\"http://tempuri.org/\">      
                                            <processo>p_impreggar</processo>      
                                            <xml1>
                                                &lt;REGISTROS_GARANTIAS&gt;
                                                    &lt;REGISTROS QTD_REG=\"$qtd_reg\"&gt;
                                                     " . implode("", $ordens) . "                                                   
                                                    &lt;/REGISTROS&gt;                                                    
                                                &lt;/REGISTROS_GARANTIAS&gt;                                                
                                            </xml1>      
                                            <CostumerId>" . $custumerid . "</CostumerId>
                                            <strUsername>" . $username  . "</strUsername>
                                            <strPassword>" . $password . "</strPassword>  
                                        </callXmlProcess>  
                                    </soap:Body></soap:Envelope>",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: text/xml"
            ),
        ));

        $response = curl_exec($curl);

        if (empty($response)) {
            return "<div class='align-center'><h3>Houve algum erro ao processar, pode ser caracter especial do cadastro do cliente ou algo do genero!</h3></div>";
        }
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return "<div class='align-center'><h3>Error #:" . $err . "</h3></div>";
        } else {
            // $this->apiNewAge->store($pneus);
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
            $xml = new \SimpleXMLElement($response);
            $array = json_decode(json_encode((array)$xml), TRUE);
            $array = $array['soapBody']['callXmlProcessResponse']['callXmlProcessResult'];
            $array = simplexml_load_string($array);
            $pneusLog = $array->REGISTROS->REGISTRO;
            $this->logNewAge->storeUpdateLog($pneusLog);
            // dd($array);
            $html = '<table id="table-log" class="table table-hover" style="width:100%">
                    <thead">
                        <tr>
                            <th>Emp</th>
                            <th>Ordem</th>
                            <th>Pedido</th>                            
                            <th>Ocorrencia</th>
                            <th>Exportada</th>                                                        
                        </tr>
                    </thead>
                    <tbody">';
            // foreach ($pneusLog as $a) {
            //     var_dump (substr($a->OCORRENCIA, 91, 8));
            //     if (substr($a->OCORRENCIA, 129, 8) == 'superior' || substr($a->OCORRENCIA, 86, 8) == 'Invalido' || substr($a->OCORRENCIA, 91, 8) == 'superior') {
            //         echo 'Verdadeiro' . '</br>';
            //     } else {
            //         echo 'false';
            //     }
            // }
            // die();
            foreach ($pneusLog as $a) {
                $html .= '
                    <tr>
                        <td>' . $a->CODIGO_EMP . '</td>
                        <td>' . $a->NUMERO_OS . '</td>
                        <td>' . $a->CHAVE_COL . '</td>
                        <td>' . $a->OCORRENCIA . '</td>
                        <td>' . $a->EXPORTADA . '</td>                      
                     </tr>';
            }
            $html .= '</tbody>';
            return $html;
        }
    }
    public function executeComando()
    {
        $stream_context_opts = [
            'http' => [
                'cache-control' => 'no-cache',
                'soapaction' => '\\"http://tempuri.org/executarComando\\"',
                'content-length' => 'length',
                'host' => '186.202.136.122',
                'content-type' => 'text/xml'
            ]
        ];
        $ctx = stream_context_create($stream_context_opts);
        $soap = new \SoapClient(
            'http://186.202.136.122/NewAgeWebServiceSetup/EASWebService.asmx?WSDL',
            array('stream_context' => $ctx, "trace" => 1)
        );
        // dd($soap->__getFunctions());
        $params = [
            'comando' => 'date()',
            'CostumerId' => 'bkp_bandag',
            'strUsername' => 'webservice',
            'strPassword' => 'erp'
        ];

        $response = $soap->executarComando($params);
        //dd($soap->__getLastRequest());
        dd($response);
    }
    public function EditOrdens()
    {
        $pneuOrdem = ApiNewAge::findOrFail($this->request->id);
        if ($this->request->modelo != 0) {
            $pneuOrdem->MODELO = $this->request->modelo;
        }
        if ($this->request->medida != 0) {
            $pneuOrdem->COD_I_MED = $this->request->medida;
        }

        $update = $pneuOrdem->save();

        if ($update == 1) {
            return response()->json(['success' => 'Ordem atualizada com sucesso!']);
        }
        return response()->json(['errors' => 'Houve algum erro!']);
    }
    public function Divergencia()
    {
        LogApiNewAge::where('ordem', $this->request->ordem)->firstOrFail();
        $ordem = $this->logNewAge->ListOrdemDivergente($this->request->ordem);

        return $ordem;
    }
}
