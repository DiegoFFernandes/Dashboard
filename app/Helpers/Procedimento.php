<?php

use App\Models\Procedimento;
use App\Models\ProcedimentoAprovador;

class ProcedimentoHelper
{
    static $saveStatus;

    static function verifyIfRealeased($procedimento)
    {
        //Consulta no banco como está o Status do procedimento por Aprovador
        $status = ProcedimentoAprovador::verifyIfReleased($procedimento->id);


        foreach ($status as $s) {
            $array[] = $s->aprovado;
        }
        if (in_array('R', $array)) {
            return self::saveStatus($procedimento->id, 'R');

        } elseif (in_array('A', $array)) {
            return self::saveStatus($procedimento->id, 'P');

        } elseif (in_array('N', $array)) {
            return self::saveStatus($procedimento->id, 'N');

        } elseif (!in_array('A', $array) || !in_array('R', $array) || !in_array('N', $array)) {
            return self::saveStatus($procedimento->id, 'L');

        }
        return false;
    }

    static function saveStatus($id, $status){
        $procedimento = Procedimento::find($id);
        $procedimento['status'] = $status;
        return $procedimento->save();
    }
}
