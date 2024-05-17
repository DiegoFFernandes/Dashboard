<?php

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Digisac
{
    private $url;    

    static function OAuthToken()
    {
        $data = [
            'grant_type' => 'password',
            'client_id' => 'api',
            'client_secret' => 'secret',
            'username' => env('DIGISAC_USER'),
            'password' => env('DIGISAC_PASSWORD'),
            'scope' => '*',
            'accountId' => ''
        ];
        // Fazendo a requisição HTTP
        $response = Http::asForm()->post('https://rhivorecap.digisac.co/api/v1/oauth/token', $data);

        // Retornando a resposta
        return json_decode($response->body());
    }
    static function SendMessage($oauth, $contact)
    {
        $baarer_token = $oauth->token_type . ' ' . $oauth->access_token;
        $url = env("URL_API_DIGISAC");

        $data = [

            'text' => 'Olá envio de pdf',
            'number' => 'chat',
            'contactId' => 'c62377c8-12da-4a29-ae99-a124029cf03a',
            'serviceId' => '3c3011aa-88d4-43ba-83dd-64b79be73b0b',
            'origin' => 'bot', // bot or user, 
            // 'file' => [
            //     'base64' => '',
            //     'mimetype' => 'application/pdf',
            // "name" => "Teste"
            // ],          
        ];

        $reponse = HTTP::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $baarer_token,
        ])->post($url . '/messages', $data);

        return json_decode($reponse);
    }
    static function ContactExists($oauth, $number)
    {
        $baarer_token = $oauth->token_type . ' ' . $oauth->access_token;
        $url = env("URL_API_DIGISAC");

        $response = HTTP::withHeaders([           
            'Authorization' => $baarer_token,
        ])->get($url.'/contacts/exists', [
            'numbers' => [$number],
            'serviceId' => 'db615bbe-ddf0-438b-b2f3-6c05e5a13eb0'
        ] );

        return $response;
    }
    static function AddContact($oauth, $contact){

        // return  $contact[0]->NM_PESSOA;
        $baarer_token = $oauth->token_type . ' ' . $oauth->access_token;
        $url = env("URL_API_DIGISAC");

        $data = [
            "unsubscribed" => false,
            "note" => "Cadastro feito pela API - ChatBot",
            "serviceId" => "db615bbe-ddf0-438b-b2f3-6c05e5a13eb0",
            // "personId" => "",
            // "defaultDepartmentId" => {},
            // "defaultUserId" => "",
            "name" => $contact[0]->NM_PESSOA,
            "internalName" => $contact[0]->NM_PESSOA,
            "alternativeName" => $contact[0]->NM_PESSOA,
            "number" => $contact[0]->NR_CELULAR
        ];

        $reponse = HTTP::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $baarer_token,
        ])->post($url . '/contacts', $data);

        return $reponse;
    }
    static function ListContacts($oauth){
        $baarer_token = $oauth->token_type . ' ' . $oauth->access_token;
        $url = env("URL_API_DIGISAC").'/contacts?where[serviceId]=db615bbe-ddf0-438b-b2f3-6c05e5a13eb0&perPage=3000';

        return $response = HTTP::withHeaders([           
            'Authorization' => $baarer_token,
        ])->get($url);
    }
}
