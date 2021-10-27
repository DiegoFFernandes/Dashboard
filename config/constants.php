<?php

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

$data_fim = mktime(23, 59, 59, date('m'), date("t"), date('Y')); //Data fim de mês
$data_inicio = mktime(0, 0, 0, date('m'), 1, date('Y')); //Data do inicio de Mes

$data_ini = date('m-d-Y', $data_inicio);
$data_fim = date('m-d-Y', $data_fim);
$today = date('m-d-Y');

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

//Nome dos 5 ultimos meses da data atual
$nMesHj = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $today)))));
$nMes30 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti30dias)))));
$nMes60 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti60dias)))));
$nMes90 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti90dias)))));
$nMes120 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti120dias)))));
$nMes150 = utf8_encode(ucfirst(strftime('%B', strtotime(str_replace('-', '/', $dti150dias)))));

return [
    'options' => [
        'dti30dias' => $dti30dias,
        'dtf30dias' => $dtf30dias,
        'dti60dias' => $dti60dias,
        'dtf60dias' => $dtf60dias,
        'dti90dias' => $dti90dias,
        'dtf90dias' => $dtf90dias,
    ],
    'meses' => [
        'nMesHj' => $nMesHj,
        'nMes30' => $nMes30,
        'nMes60' => $nMes60,
        'nMes90' => $nMes90,
    ]
];