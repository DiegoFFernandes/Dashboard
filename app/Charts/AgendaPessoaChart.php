<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class AgendaPessoaChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function legendPosition(string $position)
    {
        return $this->options([
            'legend' => [
                'position' => $position,
            ],
        ]);
    }
}
