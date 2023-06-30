<?php

class PowerbiHelper
{
    public static function processPowerbiHttpRequest($url, $header, $data, $method = 'POST')
    {
        $header[] = 'Content-Length:' . strlen($data);
        $context = [
            'http' => [
                'method'  => $method,
                'header'  => implode("\r\n", $header),
                'content' => $data
            ]
        ];
        // dd($header);
        $content = file_get_contents($url, false, stream_context_create($context));
        if ($content != false) {
            $content = json_decode($content);
        }
        return $content;

        // [
        //     'content' => $content,
        //     'headers' => $http_response_header,
        // ];
    }

    public static function getOffice360AccessToken()
    {
        $url = 'https://login.microsoftonline.com/common/oauth2/token';
        $data = http_build_query([
            'grant_type'    => 'password',
            'resource'      => 'https://analysis.windows.net/powerbi/api', //URL não muda
            'client_id'     => env('POWERBI_CLIENTE_ID'), //Id do Aplicativo
            'client_secret' => env('POWERBI_CLIENT_SECRET'), // Certificados e segredos > Segredos do Cliente
            'username'      => env('POWERBI_USERNAME'), // Usuario e senha com permissão a acessar o relatório
            'password'      => env('POWERBI_PASSWORD'), // Password do usuario acima    
            'tenant_id'     => env('TENANT_ID'), //Tenant ID Azure Not necessary
            'authentication_mode' => 'MasterUser'
        ], '', '&');
        $header = [
            "Content-Type:application/x-www-form-urlencoded",
            "return-client-request-id:true",
        ];
        $result = self::processPowerbiHttpRequest($url, $header, $data);
        if ($result) {
            return $result;
            //return $result['content'];
        } else {
            return null;
        }
    }

    public static function debugPrint($param)
    {
        print '<pre>';
        print_r($param);
        print '</pre>';
    }
}
