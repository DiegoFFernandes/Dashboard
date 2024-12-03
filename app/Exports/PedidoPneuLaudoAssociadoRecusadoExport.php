<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PedidoPneuLaudoAssociadoRecusadoExport implements FromArray, WithHeadings
{
    protected $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function array(): array
    {
        return $this->results;
    }

    public function headings(): array
    {
        return ["Empresa", "Pedido", "Pessoa", "Status"];
    }
}
