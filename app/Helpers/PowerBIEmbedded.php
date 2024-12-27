<?php

use Illuminate\Support\Facades\Http;

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

    public static function updateTablePowerBi($objects)
    {
        $office360token = self::getOffice360AccessToken();
        $datasetID = env('DATASET_ID_REDE');

        $url = "https://api.powerbi.com/v1.0/myorg/datasets/%s/refreshes";
        $url = sprintf($url, $datasetID);

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => $office360token->token_type . ' ' . $office360token->access_token,
        ];
        $body = [
            "type" => "Full",
            "objects" => [
                $objects
            ]
        ];
        return Http::withHeaders($headers)->post($url, $body);
    }

    public static function handleResponse($response, $successRoute, $errorRoute){
         // Verifica se a requisição foi bem-sucedida
         if ($response->successful()) {
            return redirect()->route($successRoute)->with(['info' => 'Atualizando tabelas, aguarde processamento no servidor!']);
        }

        // Verifica erros no lado do cliente
        if ($response->clientError()) {
            $responseData = $response->json();

            // Caso de erro específico
            if (isset($responseData['error']['message']) && $responseData['error']['code'] === 'InvalidRequest') {
                return redirect()->route($errorRoute)->with(['warning' => 'Já existe uma solicitação de atualização em andamento. Tente novamente mais tarde.']);
            }

            // Caso de erro genérico
            return redirect()->route($errorRoute)->with(['warning' => 'Erro ao atualizar as permissões do Power BI.']);
        }

        // Caso de erro no lado do servidor
        if ($response->serverError()) {
            return redirect()->route($errorRoute)->with(['error' => 'Erro interno no servidor. Tente novamente mais tarde.']);
        }

        // Caso nenhum dos anteriores seja identificado
        return redirect()->route($errorRoute)->with(['error' => 'Erro desconhecido ao processar a solicitação.']);
    
    }
}
