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
        $data = http_build_query([
            'grant_type'    => 'password',
            'resource'      => 'https://analysis.windows.net/powerbi/api', //URL não muda
            'client_id'     => '', //Id do Aplicativo
            'client_secret' => '', // Certificados e segredos > Segredos do Cliente
            'username'      => '', // Usuario e senha com permissão a acessar o relatório
            'password'      => '', // Password do usuario acima
        ], '', '&');
        $header = [
            "Content-Type:application/x-www-form-urlencoded",
            "return-client-request-id:true",
        ];
        $result = self::processPowerbiHttpRequest('https://login.microsoftonline.com/common/oauth2/token', $header, $data);
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
