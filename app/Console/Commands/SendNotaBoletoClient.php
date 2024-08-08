<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Digisac\DigiSacController;
use Illuminate\Console\Command;

class SendNotaBoletoClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:send_wpp_nota_boleto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar nota fiscal e boleto para o cliente de forma automatica pelo Digisac';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        $controller = app()->make(DigiSacController::class);

        $controller->notafiscal();

        // $this->info('Notas de Boleto enviadas com sucesso!');
    }
}
