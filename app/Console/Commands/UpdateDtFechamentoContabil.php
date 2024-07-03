<?php

namespace App\Console\Commands;

use App\Models\ParmContabil;
use Illuminate\Console\Command;

class UpdateDtFechamentoContabil extends Command
{
    public $contabil;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_fechamento_contabil';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Faz o fechamento da contabilidade de acordo com a escolha do usuario, caso estiver o parametro estiver ativo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ParmContabil $parmcontabil)
    {
        parent::__construct();
        $this->contabil = $parmcontabil;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $aberta = $this->VerificaContabilAberta();
        
        if ($aberta[0]->QTD > 0) {
            $parm_contabil = ParmContabil::select('dt_fechamento', 'status')->first();

            if ($parm_contabil['status'] == 'S') {
                $this->contabil->dt_fechamento($parm_contabil['dt_fechamento']);
            }
        }
    }
}
