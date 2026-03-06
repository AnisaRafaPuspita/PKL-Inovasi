<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InnovationRejected extends Mailable
{
    public $innovation;
    public $reason;

    public function __construct($innovation, $reason)
    {
        $this->innovation = $innovation;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Pengajuan Inovasi Ditolak')
                    ->view('emails.innovation_rejected');
    }
}
