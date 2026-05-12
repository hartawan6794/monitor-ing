<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiringMail;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendExpiryReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'subscriptions:send-reminders
                            {--days=7 : Kirim reminder untuk langganan yang expired dalam X hari}
                            {--force : Kirim ulang meskipun sudah pernah dikirimi hari ini}';

    /**
     * The console command description.
     */
    protected $description = 'Kirim email reminder untuk langganan yang mendekati tanggal expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $force = $this->option('force');

        $this->info("🔍 Mencari langganan yang expired dalam {$days} hari ke depan...");

        // Ambil langganan aktif yang akan expired dalam range hari
        $subscriptions = Subscription::with(['user', 'pricingPlan'])
            ->expiringWithin($days)
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('✅ Tidak ada langganan yang perlu diingatkan.');
            return Command::SUCCESS;
        }

        $this->info("📋 Ditemukan {$subscriptions->count()} langganan yang perlu diingatkan.");

        $sent = 0;
        $skipped = 0;

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;

            // Skip jika user tidak punya email
            if (!$user || !$user->email) {
                $this->warn("⚠️  User ID {$subscription->user_id}: Tidak punya email, dilewati.");
                $skipped++;
                continue;
            }

            // Skip jika sudah dikirimi hari ini (kecuali --force)
            if (!$force && $subscription->last_reminded_at && $subscription->last_reminded_at->isToday()) {
                $this->line("   ↳ {$user->name}: Sudah dikirimi hari ini, dilewati.");
                $skipped++;
                continue;
            }

            try {
                Mail::to($user->email)->send(new SubscriptionExpiringMail($subscription));

                // Update status reminder
                $subscription->update([
                    'last_reminded_at' => now(),
                    'remind_count' => $subscription->remind_count + 1,
                ]);

                $daysLeft = $subscription->daysUntilExpiry();
                $this->info("   ✉️  {$user->name} ({$user->email}) — Expired dalam {$daysLeft} hari");
                $sent++;

            } catch (\Exception $e) {
                $this->error("   ❌ Gagal kirim ke {$user->email}: " . $e->getMessage());
                Log::error("[REMINDER] Gagal kirim email ke {$user->email}: " . $e->getMessage());
            }
        }

        // Auto-expire langganan yang sudah melewati tanggal
        $expired = Subscription::overdue()->update(['status' => 'expired']);

        $this->newLine();
        $this->info("📊 Ringkasan:");
        $this->info("   Email terkirim : {$sent}");
        $this->info("   Dilewati      : {$skipped}");
        $this->info("   Auto-expired  : {$expired}");

        return Command::SUCCESS;
    }
}
