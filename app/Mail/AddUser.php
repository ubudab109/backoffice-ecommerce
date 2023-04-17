<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddUser extends Mailable
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
        return $this->from('superadmin@akomart.id','Superadmin')
                    ->view('emails.new-user')
                    ->with([
                        'username' => $this->credential['username'],
                        'password' => $this->credential['password'],
                        'name' => $this->credential['name']
                    ]);
    }
}
