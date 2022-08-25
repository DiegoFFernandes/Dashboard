<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProcedimentoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $setor;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $setor, $user)
    {
        $this->request = $request;
        $this->setor = $setor;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $request = $this->request;
        $setor = $this->setor;
        $user = $this->user;
        $this->subject("Procedimento");
        $this->to($user->email, $user->name)->cc(env('EMAIL_TO'));
        // $this->to("murilo.drigo@ivorecap.com.br", "Murilo Drigo");
        return $this->markdown("admin.mail.procedimento-mail", compact(
            'request',
            'setor',
            'user'
        ));
    }
}
