<?php

namespace App\Exports;

use App\Models\Financeiro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConciliacaoFinanceiraExport implements
    FromCollection,
    WithHeadings,
    WithTitle,
    WithColumnFormatting,
    WithStyles
{
    private $cd_empresa, $dt_ini, $dt_fim;

    public function __construct($cd_empresa, $dt_ini, $dt_fim)
    {
        $this->cd_empresa = $cd_empresa;
        $this->dt_ini = $dt_ini;
        $this->dt_fim = $dt_fim;
    }
    public function headings(): array
    {
        return [
            'EMPRESA',
            'NR LANC',
            'NR PROCESSO',
            'DT LANCAMENTO',
            'HISTORICO',
            'COMPLEMENTO',
            'NR DOCUMENTO',
            'PARCELA',
            'VALOR',
            'TIPO DOC',
            'CONTA DEBITO',
            'CONTA CREDITO',
            'TIPO',
            'CNPJ/CPF DEB',
            'CNPJ/CPF CRE',
            'PLANO_DEB',
            'PLANO_CRE',
            'QUESTOR DEB',
            'QUESTOR CRE'
        ];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect(Financeiro::Conciliacao($this->cd_empresa, $this->dt_ini, $this->dt_fim));
    }
    public function title(): string
    {
        return $this->dt_ini . ' - ' . $this->dt_fim;
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }
}
