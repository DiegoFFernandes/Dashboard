<?php

namespace App\Mail;

use App\Models\Procedimento;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProcedimentoChatMail extends Mailable
{
    use Queueable, SerializesModels;
    public $request;
    public $user;
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
        $user_create = User::find($request['user_created']);
        $user_approver = User::find($request['user_approver']);
        $procedimento = Procedimento::find($request['id']);
        $this->subject("Chat - Procedimento");
        $this->to($user_approver->email, $user_approver->name)->cc(env('EMAIL_TO'));
        $this->to("murilo.drigo@ivorecap.com.br", "Murilo Drigo");
        return $this->markdown("admin.mail.procedimento-chat-mail", compact(
            'request',
            'user_create',
            'user_approver',
            'procedimento'
        ));
    }
}
