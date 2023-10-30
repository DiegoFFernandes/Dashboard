<?php

namespace App\Console\Commands;

use App\Models\ParmSistema;
use Illuminate\Console\Command;

class UpdateQtdLicencaVulcano extends Command
{
    public $parmsistema;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:updatelicenca';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza as licenças do aplicativo SuperTire';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ParmSistema $sistema)
    {
        parent::__construct();
        $this->parmsistema = $sistema;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->parmsistema->UpdateLicencaColeta();
        
    }
}
