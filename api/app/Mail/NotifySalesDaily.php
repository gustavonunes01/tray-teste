<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifySalesDaily extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('Relatório do dia')->view('emails.test');
    }
}
