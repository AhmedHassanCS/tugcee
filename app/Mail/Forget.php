<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Forget extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $faker;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $faker)
    {
      $this->$name=$name;
      $this->$faker=$faker;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@tugce.com')
                    ->view('mails.forgetPassword');
    }
}
