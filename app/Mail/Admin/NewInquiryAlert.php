<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewInquiryAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✉️ New Customer Inquiry from ' . ($this->message->sender->name ?? 'User'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new_inquiry',
        );
    }
}
