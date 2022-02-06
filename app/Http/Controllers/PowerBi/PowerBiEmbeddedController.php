<?php

namespace App\Http\Controllers\PowerBi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PowerbiHelper;


class PowerBiEmbeddedController extends Controller
{
    public function index(Request $request)
    {
        $office360token = PowerbiHelper::getOffice360AccessToken();

        if (!is_null($office360token)) {

            $url = 'https://api.powerbi.com/v1.0/myorg/groups/%s/reports/%s/GenerateToken';
            $url = sprintf($url, '04d511b0-ded9-4952-9708-dfa331d1ee83', '8ee36fa6-5b54-49d4-8e66-713791f7fd3e'); 
            // no workspace escolher o relatorio e pagar na url 
            //https://app.powerbi.com/groups/04d511b0-ded9-4952-9708-dfa331d1ee83/reports/8ee36fa6-5b54-49d4-8e66-713791f7fd3e/ReportSectiondecccb8482fa6cf4f797

            $header = [
                "Authorization:{$office360token->token_type} {$office360token->access_token}",
                "content-type: application/json",
                'Content-Length:' . strlen(json_encode([]))
            ];
            
            $data = "{}";
            $content = PowerbiHelper::processPowerbiHttpRequest($url, $header, $data,'POST');          

            return view('admin.powerbi', compact('content'));

        }
    }
}
