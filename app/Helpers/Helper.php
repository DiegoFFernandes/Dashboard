<?php

use Illuminate\Support\Facades\Config;

class Helper
{
    public $request, $pessoa;

    public static function verificaMes($dt)
    {
        if ($dt == 0) {
            $datai = Config::get('constants.options.dti');
            $dataf = Config::get('constants.options.dtf');
            $nmes =  Config::get('constants.meses.nMesHj');
        } elseif ($dt == 30) {
            $datai = Config::get('constants.options.dti30dias');
            $dataf = Config::get('constants.options.dtf30dias');
            $nmes =  Config::get('constants.meses.nMes30');
        } elseif ($dt == 60) {
            $datai = Config::get('constants.options.dti60dias');
            $dataf = Config::get('constants.options.dtf60dias');
            $nmes =  Config::get('constants.meses.nMes60');
        } elseif ($dt == 90) {
            $datai = Config::get('constants.options.dti90dias');
            $dataf = Config::get('constants.options.dtf90dias');
            $nmes =  Config::get('constants.meses.nMes90');
        } elseif ($dt == 120) {
            $datai = Config::get('constants.options.dti120dias');
            $dataf = Config::get('constants.options.dtf120dias');
            $nmes =  Config::get('constants.meses.nMes120');
        } elseif ($dt == 150) {
            $datai = Config::get('constants.options.dti150dias');
            $dataf = Config::get('constants.options.dtf150dias');
            $nmes =  Config::get('constants.meses.nMes150');
        } elseif ($dt == 180) {
            $datai = Config::get('constants.options.dti180dias');
            $dataf = Config::get('constants.options.dtf180dias');
            $nmes =  Config::get('constants.meses.nMes180');
        } elseif ($dt == 210) {
            $datai = Config::get('constants.options.dti210dias');
            $dataf = Config::get('constants.options.dtf210dias');
            $nmes =  Config::get('constants.meses.nMes210');
        } elseif ($dt == 240) {
            $datai = Config::get('constants.options.dti240dias');
            $dataf = Config::get('constants.options.dtf240dias');
            $nmes =  Config::get('constants.options.nMes240');
        } elseif ($dt == 270) {
            $datai = Config::get('constants.options.dti270dias');
            $dataf = Config::get('constants.options.dtf270dias');
            $nmes =  Config::get('constants.options.nMes270');
        } elseif ($dt == 300) {
            $datai = Config::get('constants.options.dti300dias');
            $dataf = Config::get('constants.options.dtf300dias');
            $nmes =  Config::get('constants.options.nMes300');
        } elseif ($dt == 330) {
            $datai = Config::get('constants.options.dti330dias');
            $dataf = Config::get('constants.options.dtf330dias');
            $nmes =  Config::get('constants.options.nMes330');
        }

        return array("dti" => $datai, "dtf" => $dataf, 'nmes' => $nmes);
    }
    public static function VerifyRegion($local)
    {
        return $local == 'firebird_campina' ? 'SUL' : 'NORTE';
    }
    public function searchCliente()
    {
        $data = [];

        if ($this->request->has('q')) {
            $search = $this->request->q;
            $data = $this->pessoa->FindPessoaJunsoftAll($search);
        }
        return response()->json($data);
    }
    public static function RemoveSpecialChar($str)
    {
        return preg_replace('/[@\.\;\_\ \&\/\-\(\)]+/', '', $str);
    }
    public static function is_empty_object($object)
    {
        foreach ($object as $o) return false;

        return true;
    }
    public static function Get_Client_Ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    
    public static function ConvertFormatText($results){
        // Garantir que os dados estejam em UTF-8
        $results = array_map(function ($result) {
            return array_map(function ($value) {
                return mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
            }, (array) $result);
        }, $results);
        return $results;
    }
}
