<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    public Subscription $subscription;
    public int $daysLeft;

    /**
     * Create a new message instance.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
        $this->daysLeft = $subscription->daysUntilExpiry();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $urgency = $this->daysLeft <= 1 ? '🔴' : ($this->daysLeft <= 3 ? '🟡' : '⏰');

        return new Envelope(
            subject: "{$urgency} Langganan DashMo Anda Akan Berakhir dalam {$this->daysLeft} Hari",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription_expiring',
        );
    }
}
