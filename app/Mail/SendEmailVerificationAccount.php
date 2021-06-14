<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailVerificationAccount extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param User object
     */
     public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.confirm_account')->with([
            'token' => $this->user->email_verification_token, 
            'email' => $this->user->email
        ]);
    }
}
