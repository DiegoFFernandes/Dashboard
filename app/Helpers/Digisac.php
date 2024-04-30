<?php

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Digisac
{
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
    static function SendMessage($oauth){
        $baarer_token = $oauth->token_type.' '.$oauth->access_token;

        $data = [
            
                'text' => 'Olá envio de pdf',
                'type' => 'chat',
                'contactId' => 'c62377c8-12da-4a29-ae99-a124029cf03a',
                'userId' => '3c3011aa-88d4-43ba-83dd-64b79be73b0b',
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
        ])->post('https://rhivorecap.digisac.co/api/v1/messages', $data);

        return json_decode($reponse);
    }
}
