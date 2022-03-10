<?php

function formatDate($value, $format = 'd-m-Y')
{
    //$value = date('d/m/Y', $value);
    // Utiliza a classe de Carbon para converter ao formato de data ou hora desejado
    return Carbon\Carbon::createFromFormat('m-d-Y', $value)->format('d/m/Y');
    
}
