<?php

namespace App\Console;

use App\Console\Commands\UpdateTipoPessoaVencimento;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        UpdateTipoPessoaVencimento::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();send:updatetipopessoa
        // $schedule->command('send:updatetipopessoa')->everyMinute();
        $schedule->command('send:updatelicenca')->everyMinute();
        $schedule->command('send:update_fechamento_contabil')->everySixHours();
        $schedule->command('send:send_wpp_nota_boleto')->everyMinute();
        $schedule->command('send:send_wpp_boleto')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
