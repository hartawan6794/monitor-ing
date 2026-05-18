<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SubscriptionExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    public Subscription $subscription;
    public int $daysLeft;
    public string $parsedSubject;
    public string $parsedBody;

    /**
     * Create a new message instance.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
        $this->daysLeft = $subscription->daysUntilExpiry();

        // Get template from database
        $template = EmailTemplate::firstOrCreate(
            ['name' => 'subscription_expiring'],
            [
                'subject' => '⏰ Langganan DashMo Anda Akan Berakhir',
                'body' => '<h1 style="color: #f1f5f9; font-size: 24px; font-weight: 700; text-align: center; margin: 0 0 8px; letter-spacing: -0.5px;">Halo, [USER_NAME]!</h1><p style="color: #94a3b8; font-size: 15px; text-align: center; margin: 0 0 28px; line-height: 1.6;">Langganan DashMo Anda akan berakhir dalam <strong style="color: #818cf8;">[DAYS_LEFT] hari</strong> (pada [EXPIRED_DATE]). Perpanjang sekarang agar tim Anda tetap bisa memantau penjualan tanpa gangguan.</p>'
            ]
        );

        $expiredDate = $subscription->expired_at ? Carbon::parse($subscription->expired_at)->format('d F Y') : '-';

        // Replace placeholders
        $replacements = [
            '[USER_NAME]' => $subscription->user->name ?? 'Pelanggan',
            '[DAYS_LEFT]' => $this->daysLeft,
            '[PLAN_NAME]' => $subscription->pricingPlan->name ?? 'Paket',
            '[EXPIRED_DATE]' => $expiredDate,
        ];

        $this->parsedSubject = str_replace(array_keys($replacements), array_values($replacements), $template->subject);
        $this->parsedBody = str_replace(array_keys($replacements), array_values($replacements), $template->body);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->parsedSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription_expiring',
            with: [
                'parsedBody' => $this->parsedBody
            ]
        );
    }
}
