<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbsensiReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
   public $data;
    public $imagePath;

    public function __construct($data)
    {
        $this->data = $data;
        // $this->imagePath = $imagePath;
    }

    public function build()
    {
        return $this->markdown('emails.absensi_report')
                    ->with('data', $this->data)
                   ;
    }
}
