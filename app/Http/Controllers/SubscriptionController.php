<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use App\Models\PricingPlan;
use App\Mail\SubscriptionExpiringMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    /**
     * Tampilkan halaman daftar langganan.
     */
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'pricingPlan'])
            ->orderByDesc('created_at')
            ->get();

        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Tampilkan form buat langganan baru.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $plans = PricingPlan::orderBy('order')->get();

        return view('subscriptions.create', compact('users', 'plans'));
    }

    /**
     * Simpan langganan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pricing_plan_id' => 'required|exists:pricing_plans,id',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
            'notes' => 'nullable|string|max:500',
        ]);

        Subscription::create([
            'user_id' => $request->user_id,
            'pricing_plan_id' => $request->pricing_plan_id,
            'starts_at' => $request->starts_at,
            'expires_at' => $request->expires_at,
            'status' => 'active',
            'notes' => $request->notes,
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Langganan berhasil ditambahkan!');
    }

    /**
     * Perpanjang langganan (buat record baru, nonaktifkan yang lama).
     */
    public function renew(Request $request, $id)
    {
        $request->validate([
            'expires_at' => 'required|date|after:today',
        ]);

        $old = Subscription::findOrFail($id);
        $old->update(['status' => 'expired']);

        Subscription::create([
            'user_id' => $old->user_id,
            'pricing_plan_id' => $old->pricing_plan_id,
            'starts_at' => now()->toDateString(),
            'expires_at' => $request->expires_at,
            'status' => 'active',
            'notes' => 'Perpanjangan dari subscription #' . $old->id,
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Langganan berhasil diperpanjang!');
    }

    /**
     * Kirim email reminder manual.
     */
    public function sendReminder($id)
    {
        $subscription = Subscription::with(['user', 'pricingPlan'])->findOrFail($id);

        if (!$subscription->user || !$subscription->user->email) {
            return redirect()->back()->with('error', 'User tidak memiliki email.');
        }

        try {
            Mail::to($subscription->user->email)->send(new SubscriptionExpiringMail($subscription));

            $subscription->update([
                'last_reminded_at' => now(),
                'remind_count' => $subscription->remind_count + 1,
            ]);

            return redirect()->back()->with('success', "Email reminder berhasil dikirim ke {$subscription->user->email}!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    /**
     * DataTables JSON (untuk AJAX jika diperlukan).
     */
    public function getData()
    {
        return Subscription::with(['user', 'pricingPlan'])->orderByDesc('created_at')->get();
    }
}
