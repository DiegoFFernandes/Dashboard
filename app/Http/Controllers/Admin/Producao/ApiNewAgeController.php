<?php

namespace App\Http\Controllers\Admin\Producao;

use App\Http\Controllers\Controller;
use App\Models\ApiNewAge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ApiNewAgeController extends Controller
{
    protected $soapWrapper;

    public function __construct(ApiNewAge $api, Request $request)
    {
        $this->request = $request;
        $this->apiNewAge = $api;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        $title_page   = 'Exportação Automatica';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        $pneus = $this->apiNewAge->pneusBGW();
        return view('admin.producao.garantia-bgw', compact('pneus', 'title_page', 'user_auth', 'uri'));
    }

    public function RemoveSpecialChar($str)
    {
        return preg_replace('/[0-9\@\.\;\&]+/', '', $str);
    }
    public function InsertHora($hora){
        return empty($hora) ? "07:16:32" : $hora;
    }
    public function RemovePCasa($str){
        return preg_replace('/PCASA /', '', $str); 
    }
    
    public function callXmlProcess()
    {
        $pneus = $this->apiNewAge->pneusBGW();
        $qtd_reg = count($pneus); 

        foreach ($pneus as $p) {
            $ordens[] = "&lt;REGISTRO ORD_NUMERO=\"$p->ORD_NUMERO\"&gt;
            &lt;ORD_CODBTS&gt;39193&lt;/ORD_CODBTS&gt;
            &lt;ORD_NUMERO&gt;$p->ORD_NUMERO&lt;/ORD_NUMERO&gt;
            &lt;ORD_DATEMI&gt;" . Carbon::createFromFormat('Y-m-d', $p->ORD_DATEMI)->format('d/m/Y') . "&lt;/ORD_DATEMI&gt;
            &lt;ORD_HOREMI&gt;".$this->InsertHora($p->ORD_HOREMI)."&lt;/ORD_HOREMI&gt;
            &lt;NUM_NF&gt;$p->NUM_NF&lt;/NUM_NF&gt;
            &lt;DATA_NF&gt;" . Carbon::createFromFormat('Y-m-d', $p->DATA_NF)->format('d/m/Y') . "&lt;/DATA_NF&gt;
            &lt;CLI_CPF&gt;$p->CLI_CPF&lt;/CLI_CPF&gt;
            &lt;CLI_NOME&gt; " . $this->RemoveSpecialChar($p->CLI_NOME) . ";&lt;/CLI_NOME&gt;
            &lt;CLI_CEP&gt;$p->CLI_CEP&lt;/CLI_CEP&gt;
            &lt;CLI_TLOGRA&gt;&lt;/CLI_TLOGRA&gt;
            &lt;CLI_LOGRAD&gt;$p->CLI_LOGRAD&lt;/CLI_LOGRAD&gt;
            &lt;CLI_NUMERO&gt;$p->CLI_NUMERO&lt;/CLI_NUMERO&gt;
            &lt;CLI_COMPL&gt;$p->CLI_COMPL&lt;/CLI_COMPL&gt;
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
            &lt;MEDIDA&gt;".$this->RemovePCasa($p->MEDIDA)."&lt;/MEDIDA&gt;
            &lt;CONSTRUCAO&gt;RADIAL&lt;/CONSTRUCAO&gt;
            &lt;BANDA&gt;$p->BANDA&lt;/BANDA&gt;
            &lt;MATRICULA&gt;$p->MATRICULA&lt;/MATRICULA&gt;
            &lt;NUM_FOGO&gt;$p->NUM_FOGO&lt;/NUM_FOGO&gt;
            &lt;DOT&gt;$p->DOT&lt;/DOT&gt;
            &lt;MARCA&gt;$p->MARCA&lt;/MARCA&gt;
            &lt;MODELO&gt;$p->MODELO&lt;/MODELO&gt;
            &lt;CICLOVIDA&gt;$p->CICLOVIDA&lt;/CICLOVIDA&gt;
            &lt;TIPO_GAR&gt;$p->COD_I_TPGAR&lt;/TIPO_GAR&gt;
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
            &lt;COD_I_EMP&gt;26844&lt;/COD_I_EMP&gt;
            &lt;COD_I_MARCA&gt;$p->COD_I_MARCA&lt;/COD_I_MARCA&gt;
            &lt;COD_I_MOD&gt;$p->MODELO&lt;/COD_I_MOD&gt;
            &lt;COD_I_MED&gt;".$this->RemovePCasa($p->COD_I_MED)."&lt;/COD_I_MED&gt;
            &lt;COD_I_BANDA&gt;$p->COD_I_BANDA&lt;/COD_I_BANDA&gt;
            &lt;COD_I_TPGAR&gt;$p->COD_I_TPGAR&lt;/COD_I_TPGAR&gt;
            &lt;COD_I_EX_I&gt;1I&lt;/COD_I_EX_I&gt;
            &lt;COD_I_EX_NDI&gt;NDI&lt;/COD_I_EX_NDI&gt;
            &lt;COD_I_EX_F&gt;1F&lt;/COD_I_EX_F&gt;
        &lt;/REGISTRO&gt;";
        }

        // return $ordens;
        if (empty($ordens)) {
            return "<div class='align-center'><h3>Não possui Pneus a processar!</h3></div>";
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
                                            <CostumerId>".env('CUSTUMERID_NEWAGE_SUL')."</CostumerId>
                                            <strUsername>".env('USERNAME_NEWAGE_SUL')."</strUsername>
                                            <strPassword>".env('PASSWORD_NEWAGE_SUL')."</strPassword>  
                                        </callXmlProcess>  
                                    </soap:Body></soap:Envelope>",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: text/xml"
            ),
        ));

        $response = curl_exec($curl);
        
        $saveOrdens = $this->apiNewAge->store($pneus);

        if(empty($response)){            
            return "<div class='align-center'><h3>Houve algum erro ao processar, pode ser caracter especial do cadastro do cliente ou algo do genero!</h3></div>";
        }
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return "<div class='align-center'><h3>Error #:" . $err . "</h3></div>";
        } else {
            $this->apiNewAge->store($pneus);
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
            $xml = new \SimpleXMLElement($response);
            $array = json_decode(json_encode((array)$xml), TRUE);
            $array = $array['soapBody']['callXmlProcessResponse']['callXmlProcessResult'];
            $array = simplexml_load_string($array);
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

            foreach ($array->REGISTROS->REGISTRO as $a) {
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
}
