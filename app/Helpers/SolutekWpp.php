<?php

class solutekWpp
{

    static function DataMsgWpp($input)
    {

        $phones = explode(',', env('PHONE_TICKETS_MANUTENCION'));

        $email = env('EMAIL_SOLUTEK');
        $token = env('TOKEN_SOLUTEK');
        $idapp = env('IDAPP_SOLUTEK');
        $emoji = "nao"; //"sim" ou "nao"


        $dados['email'] = $email;
        $dados['token'] = $token;
        $dados['idapp'] = $idapp;

        // funçoes: obter_fila / status / enviar / delay / enviar_botoes

        $funcao = 'enviar';

        foreach ($phones as $p) {
            $idmsg = rand();
            $whatsapp = $p; //EX: 5581988888888
            $mensagem = "Olá, foi aberto um chamado cód. *" . $input[0]->id . "* pelo responsavel, *" . $input[0]->name . "*, para a maquina *" . $input[0]->maquina . "*, com o problema *" . $input[0]->tp_problema . "*, a maquina *" . $input[0]->parada . "* está parada.\nEntre no portal para mais detalhes.\nNão e necessario responder está mensagem.";
            $dados['email'] = $email;
            $dados['token'] = $token;
            $dados['idapp'] = $idapp;
            $dados['idmsg'] = $idmsg;
            $dados['midia'] = "texto"; //"texto" , "imagem" ou "arquivo"
            $dados['url_anexo'] = ""; //opcional. necessário se midia="imagem" ou midia="arquivo";
            $dados['whatsapp'] = $whatsapp;
            $dados['mensagem'] = $mensagem;
            $dados['emoji'] = $emoji;
            
            $retorno = self::SendWpp($dados, 'https://www.solutek.online/api/whatsapp/gateway/json/' . $funcao . '');
        }

        // $erro = $retorno->erro;
        // $sobre_o_erro = $retorno->sobre_o_erro;
        // $whatsapp_retorno = $retorno->whatsapp;
        // $mensagem_retorno = $retorno->mensagem;
        // $status = $retorno->status;
        // $idlog = $retorno->idlog;
    }
    static function SendWpp($dados, $link)
    {
        $endpoint = $link;

        $curl = curl_init($endpoint);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POST, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $dados);

        $executa_api = curl_exec($curl);

        curl_close($curl);

        return json_decode($executa_api);
    }
}
