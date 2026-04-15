<?php

namespace App\Http\Controllers;

use App\Models\PricingPlan;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $pricing_plans = PricingPlan::with('features')->orderBy('order')->get();
        return view('home', compact('pricing_plans'));
    }
}
