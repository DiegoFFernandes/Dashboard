<?php

namespace App\Http\Controllers\Admin\Producao;

use App\Http\Controllers\Controller;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Http\Request;


class ApiNewAgeController extends Controller
{
    protected $soapWrapper;
    public function __construct(SoapWrapper $soapWrapper)
    {
        $this->soapWrapper = $soapWrapper;
    }
    public function index(Request $request)
    {
        // $this->soapWrapper->add('Comando', function ($service) {
        //     $service
        //         ->wsdl('http://186.202.136.122/NewAgeWebServiceSetup/EASWebService.asmx?WSDL')
        //         ->trace(true);
        // });        
        // $results = $this->soapWrapper->call('Comando.executarComando', [
        //     'comando' => 1,
        //     'CostumerId' => 2,
        //     'strUsername' => 'teste',
        //     'strPassword' => 123,
        // ]);

        $soap = new \SoapClient('http://186.202.136.122/NewAgeWebServiceSetup/EASWebService.asmx?WSDL');
        // dd($soap->__getFunctions());
        $params = [
            'executarComando' => [
                'comando' => 'pwd',
                'CostumerId' => '1',
                'strUsername' => 'teste',
                'strPassword' => 'teste2'
            ]
        ];
        $response = $soap->executarComando($params);
        dd($response);
    }
}
