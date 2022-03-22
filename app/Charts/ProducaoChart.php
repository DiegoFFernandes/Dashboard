<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\ChartJs\Chart;

class ProducaoChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $this->displayAxes(false);
        // $this->minimalist(true);
        // $this->scaleShowGridLines(false);
    }
}
