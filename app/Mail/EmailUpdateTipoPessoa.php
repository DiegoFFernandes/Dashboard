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
        $this->subject("Alteração de Cliente para ZDD");
        $this->to('cobranca@ivorecap.com.br', 'Rafael Cazante')
            ->to('juridico@ivorecap.com.br', 'Maria Vitoria')
            ->cc('ti.paranavai@ivorecap.com.br', 'Diego Ferreira');

        return $this->markdown('admin.mail.updatetipopessoa', compact('pessoas'));
    }
}
