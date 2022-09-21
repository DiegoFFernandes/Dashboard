<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ValidateDeleteEmailWebhook extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $request = $this->request;
        
        $this->subject("Email Validado");
        $this->to('luiz.ricardo@ivorecap.com.br', 'Luiz Ricardo')
        ->to('lucas.felipe@ivorecap.com.br', 'Lucas Felipe')
        ->cc(env('EMAIL_TO'));        
        return $this->markdown('admin.mail.valida-delete-email-iagente', compact('user', 'request'));
    }
}
