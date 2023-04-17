<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $credential;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($credential)
    {
        //
        $this->credential = $credential;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('superadmin@backoffice.com','Superadmin')
                    ->view('emails.forget-password')
                    ->with([
                        'username' => $this->credential['username'],
                        'name' => $this->credential['name']
                    ]);
    }
}
