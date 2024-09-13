<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Digisac\DigiSacController;
use Illuminate\Console\Command;

class SendBoletoClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:send_wpp_boleto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia somente os boletos não enviados com o comando Envia nota e boleto!';

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

        $controller->Boleto();
    }
}
