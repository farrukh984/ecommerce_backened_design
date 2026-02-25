<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewReviewAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $review;

    public function __construct($review)
    {
        $this->review = $review;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⭐ New Product Review for ' . ($this->review->product->name ?? 'Product'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new_review',
        );
    }
}
