<?php

namespace App\Console\Commands;

use App\Mail\EmailUpdateTipoPessoa;
use App\Models\Pessoa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UpdateTipoPessoaVencimento extends Command
{
    public $pessoas;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:updatetipopessoa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza o tipo de pessoa na base de dados junsoft quando o vencimento 
    for mais de 365 dias';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Pessoa $pessoa)
    {
        parent::__construct();
        $this->pessoas = $pessoa;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pessoas = $this->pessoas->findTipoPessoaVencimento();        
        $this->pessoas->UpdateTipoPessoa($pessoas);
        Mail::send(new EmailUpdateTipoPessoa($pessoas));       
        
    }
}
