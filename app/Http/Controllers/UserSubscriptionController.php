<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PricingPlan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $subscription = Subscription::with('pricingPlan')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>=', now()->toDateString())
            ->orderBy('expires_at', 'desc')
            ->first();

        $history = Subscription::with('pricingPlan')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.subscription.index', compact('subscription', 'history'));
    }

    public function plans()
    {
        $pricing_plans = PricingPlan::with('features')->orderBy('order')->get();
        return view('user.subscription.plans', compact('pricing_plans'));
    }

    public function checkout($id)
    {
        $plan = PricingPlan::findOrFail($id);
        return view('user.subscription.checkout', compact('plan'));
    }

    public function processPayment(Request $request, $id)
    {
        $user = Auth::user();
        $plan = PricingPlan::findOrFail($id);
        $duration = $plan->duration_days ?? 30;

        // Cek apakah ada langganan aktif, jika ada, set jadi expired
        $activeSub = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($activeSub) {
            $activeSub->update(['status' => 'expired']);
        }

        // Buat langganan baru
        Subscription::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $plan->id,
            'starts_at' => now()->toDateString(),
            'expires_at' => now()->addDays($duration)->toDateString(),
            'status' => 'active',
            'notes' => 'Pembayaran simulasi via User Dashboard',
        ]);

        return redirect()->route('my-subscription.index')->with('success', 'Pembayaran berhasil! Langganan Anda sekarang aktif.');
    }
}
