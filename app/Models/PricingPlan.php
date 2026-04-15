<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasFactory;
      protected $table = 'pricing_plans';

    protected $fillable = [
        'name', 'price', 'price_subtext', 'button_text', 'button_link', 'is_featured', 'badge_text', 'order'
    ];

    public function features()
    {
        return $this->hasMany(PricingFeature::class, 'pricing_plan_id')->orderBy('order');
    }
}