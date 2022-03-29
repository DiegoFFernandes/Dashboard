<?php

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

$data_fim = mktime(23, 59, 59, date('m'), date("t"), date('Y')); //Data fim de mês
$data_inicio = mktime(0, 0, 0, date('m'), 1, date('Y')); //Data do inicio de Mes

$data_ini = date('m-d-Y', $data_inicio);
$data_fim = date('m-d-Y', $data_fim);
$today = date('m-d-Y');

//Data 6 / 60 / 120 dias da data atual
$dt6days = date('Y-m-d', strtotime('-6 days', strtotime(str_replace('-', '/', $today))));
$dt60days = date('Y-m-d', strtotime('-60 days', strtotime(str_replace('-', '/', $today))));
$dt120days = date('Y-m-d', strtotime('-120 days', strtotime(str_replace('-', '/', $today))));


//Data dos ultimos 6 meses inicio e de termino a partir da data atual
$dti30dias = date('m-d-Y', strtotime('-1 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf30dias = date('m-t-Y', strtotime('-1 month', strtotime(str_replace('-', '/', $data_ini))));
$dti60dias = date('m-d-Y', strtotime('-2 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf60dias = date('m-t-Y', strtotime('-2 month', strtotime(str_replace('-', '/', $data_ini))));
$dti90dias = date('m-d-Y', strtotime('-3 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf90dias = date('m-t-Y', strtotime('-3 month', strtotime(str_replace('-', '/', $data_ini))));
$dti120dias = date('m-d-Y', strtotime('-4 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf120dias = date('m-t-Y', strtotime('-4 month', strtotime(str_replace('-', '/', $data_ini))));
$dti150dias = date('m-d-Y', strtotime('-5 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf150dias = date('m-t-Y', strtotime('-5 month', strtotime(str_replace('-', '/', $data_ini))));
$dti180dias = date('m-d-Y', strtotime('-6 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf180dias = date('m-t-Y', strtotime('-6 month', strtotime(str_replace('-', '/', $data_ini))));
$dti210dias = date('m-d-Y', strtotime('-7 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf210dias = date('m-t-Y', strtotime('-7 month', strtotime(str_replace('-', '/', $data_ini))));
$dti240dias = date('m-d-Y', strtotime('-8 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf240dias = date('m-t-Y', strtotime('-8 month', strtotime(str_replace('-', '/', $data_ini))));
$dti270dias = date('m-d-Y', strtotime('-9 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf270dias = date('m-t-Y', strtotime('-9 month', strtotime(str_replace('-', '/', $data_ini))));
$dti300dias = date('m-d-Y', strtotime('-10 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf300dias = date('m-t-Y', strtotime('-10 month', strtotime(str_replace('-', '/', $data_ini))));
$dti330dias = date('m-d-Y', strtotime('-11 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf330dias = date('m-t-Y', strtotime('-11 month', strtotime(str_replace('-', '/', $data_ini))));
$dti360dias = date('m-d-Y', strtotime('-12 month', strtotime(str_replace('-', '/', $data_ini))));
$dtf360dias = date('m-t-Y', strtotime('-12 month', strtotime(str_replace('-', '/', $data_ini))));


//Nome dos 5 ultimos meses da data atual
$nMesHj = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $today)))));
$nMes30 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti30dias)))));
$nMes60 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti60dias)))));
$nMes90 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti90dias)))));
$nMes120 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti120dias)))));
$nMes150 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti150dias)))));
$nMes180 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti180dias)))));
$nMes210 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti210dias)))));
$nMes240 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti240dias)))));
$nMes270 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti270dias)))));
$nMes300 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti300dias)))));
$nMes330 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti330dias)))));
$nMes360 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti360dias)))));

return [
    'options' => [
        'today' => $today,
        'dti' => $data_ini,
        'dtf' => $data_fim,
        'dt6days' => $dt6days,
        'dt60days' => $dt60days,
        'dti30dias' => $dti30dias,
        'dtf30dias' => $dtf30dias,
        'dti60dias' => $dti60dias,
        'dtf60dias' => $dtf60dias,
        'dti90dias' => $dti90dias,
        'dtf90dias' => $dtf90dias,
        'dt120days' => $dt120days,
        'dti120dias' => $dti120dias,
        'dtf120dias' => $dtf120dias,
        'dti150dias' => $dti150dias,
        'dtf150dias' => $dtf150dias,
        'dti180dias' => $dti180dias,
        'dtf180dias' => $dtf180dias,
        'dti210dias' => $dti210dias,
        'dtf210dias' => $dtf210dias,
        'dti240dias' => $dti240dias,
        'dtf240dias' => $dtf240dias,
        'dti270dias' => $dti270dias,
        'dtf270dias' => $dtf270dias,
        'dti300dias' => $dti300dias,
        'dtf300dias' => $dtf300dias,
        'dti330dias' => $dti330dias,
        'dtf330dias' => $dtf330dias,
        'dti360dias' => $dti360dias,
        'dtf360dias' => $dtf360dias,
    ],
    'meses' => [
        'nMesHj' => $nMesHj,
        'nMes30' => $nMes30,
        'nMes60' => $nMes60,
        'nMes90' => $nMes90,
        'nMes120' => $nMes120,
        'nMes150' => $nMes150,   
        'nMes180' => $nMes180,
        'nMes210' => $nMes210,
        'nMes240' => $nMes240,
        'nMes270' => $nMes270,
        'nMes300' => $nMes300,
        'nMes330' => $nMes330,   
        'nMes360' => $nMes360,   
    ]
];