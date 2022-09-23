<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailProblemWebHookMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $request = $this->request;
        $this->subject("Email com Problema");
        $this->to('luiz.ricardo@ivorecap.com.br', 'Luiz Ricardo')
        ->to('bernardo.almeida@ivorecap.com.br', 'Bernardo')
        ->cc(env('EMAIL_CC'));  
        return $this->markdown('admin.mail.problem-email-iagente', compact('request'));
    }
}
