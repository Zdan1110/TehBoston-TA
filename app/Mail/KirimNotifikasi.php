<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KirimNotifikasi extends Mailable
{
    use Queueable, SerializesModels;

    public $nama_lengkap, $status;
    public function __construct($nama_lengkap, $status)
    {
        $this->nama_lengkap = $nama_lengkap;
        $this->status = $status;
    }


    public function build()
    {
        return $this->subject('Status Franchise Anda Telah Diperbarui')
                    ->view('status_updated');
    }
}
