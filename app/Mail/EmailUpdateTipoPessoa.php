<?php

namespace App\Mail;

use App\Models\Pessoa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailUpdateTipoPessoa extends Mailable
{
    use Queueable, SerializesModels;
    public $pessoa;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pessoa)
    {
        $this->pessoa = $pessoa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pessoas = $this->pessoa;


        if (!empty($pessoas)) {
            $this->subject("Alteração de Cliente para ZDD");
            $this->to('ti.campina@ivorecap.com.br', 'Diego Ferreira')
            // ->to('juridico@ivorecap.com.br', 'Maria Vitoria')
            // ->to('ti.paranvai@ivorecap.com.br', 'Evandro Santos')
            ->cc('cobranca@ivorecap.com.br', 'Rafael Cazante');

            return $this->markdown('admin.mail.updatetipopessoa', compact('pessoas'));
        }
    }
}
