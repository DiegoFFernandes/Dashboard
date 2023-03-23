<?php

class solutekWpp
{
    public $input, $status, $local;

    static function DataMsgWpp($input, $status, $local)
    {

        if ($status == 'acompanhamento') {
            if ($input->type == "C") { //Se a mensagem for do criador dispara a mensagem para os responsaveis.
                if ($local == "NORTE") {
                    $phones = explode(',', env('PHONE_TICKETS_MANUTENCION_NORTE'));
                } else {
                    $phones = explode(',', env('PHONE_TICKETS_MANUTENCION_SUL'));
                }

                $mensagem = "Olá, teve um andamento no chamado da manunteção: *" . $input->cd_ticket . "*, pelo usuario *" . $input->user_create . "* com a seguinte descrição, *" . $input->message . "* , para mais detalhes acesse o portal.";
            } else { //Se a mensagem for do resolvedor dispara a mensagem para o criador.
                $phones = ['55' . $input->phone_create];
                $mensagem = "Olá, teve um andamento no chamado da manutenção: *" . $input->cd_ticket . "*, pelo usuario *" . $input->user_resolve . "* com a seguinte descrição, *" . $input->message . "* , para mais detalhes acesse o portal.";
            }
        } elseif ($status == 'finalizado') {
            $phones = ['55' . $input->phone_create];
            $mensagem = "Olá, o chamado da manutenção: *" . $input->cd_ticket . "*, foi finalizado pelo usuario *" . $input->user_resolve . "* com a seguinte descrição, *" . $input->message . "* , para mais detalhes acesse o portal.";
        } else {
            if ($local == "NORTE") {
                $phones = explode(',', env('PHONE_TICKETS_MANUTENCION_NORTE'));
            } else {
                $phones = explode(',', env('PHONE_TICKETS_MANUTENCION_SUL'));
            }
            $mensagem = "Olá, foi aberto um chamado cód. *" . $input[0]->id . "* pelo responsavel, *" . $input[0]->name . "*, para a maquina *" . $input[0]->maquina . "*, com o problema *" . $input[0]->tp_problema . "*, a maquina *" . $input[0]->parada . "* está parada.\nEntre no portal para mais detalhes.\nNão e necessario responder está mensagem.";
        }

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
    static function LoadingData()
    {
    }
}
