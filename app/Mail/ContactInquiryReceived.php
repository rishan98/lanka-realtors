<?php

namespace App\Mail;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactInquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public ContactInquiry $inquiry;

    public function __construct(ContactInquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function build()
    {
        return $this
            ->subject('New contact form message — '.config('app.name'))
            ->replyTo($this->inquiry->email, $this->inquiry->name)
            ->view('emails.contact-inquiry');
    }
}
