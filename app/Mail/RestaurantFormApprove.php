<?php

namespace App\Mail;

use Barryvdh\DomPDF\PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\App;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RestaurantFormApprove extends Mailable
{
    use Queueable, SerializesModels;

    public $details, $pdf2;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
        $data['username'] = $this->details['username'];
        $data['password'] = $this->details['password'];
        $pdf = App::make('dompdf.wrapper');
        $this->pdf2 = $pdf->loadView('emails.createPdf', $data);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Samquicksal Application Form')->markdown('emails.restuarant-form-approve')->attachData($this->pdf2->output(), 'Account Details.pdf');
    }
}
