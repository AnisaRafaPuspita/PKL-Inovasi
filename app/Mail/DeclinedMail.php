<?php

namespace App\Mail;

use App\Models\Innovation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeclinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Innovation $innovation;
    public string $reason;

    public function __construct(Innovation $innovation, string $reason)
    {
        $this->innovation = $innovation;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Perbaikan Diperlukan: Inovasi Ditolak')
            ->view('emails.innovation_declined');
    }
}