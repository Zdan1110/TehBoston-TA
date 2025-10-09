<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KirimNotifikasiBerlangganan extends Mailable
{
    use Queueable, SerializesModels;

    public $nama_franchise, $akses;
    public function __construct($nama_franchise, $akses)
    {
        $this->nama_franchise = $nama_franchise;
        $this->akses = $akses;
    }


    public function build()
    {
        return $this->subject('Status Berlangganan Anda Telah Diperbarui')
                    ->view('statusberlangganan_update');
    }
}
