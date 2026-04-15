<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $basic = \App\Models\PricingPlan::create([
            'name' => 'Basic',
            'price' => 'Free Tier',
            'price_subtext' => '/forever',
            'button_text' => 'Mulai Sekarang',
            'button_link' => '#',
            'is_featured' => false,
            'order' => 1,
        ]);

        $basic->features()->createMany([
            ['name' => 'Penjualan & Order', 'order' => 1],
            ['name' => 'Riwayat Transaksi', 'order' => 2],
            ['name' => 'Retur Penjualan', 'order' => 3],
            ['name' => 'Laporan Harian Penjualan', 'order' => 4],
        ]);

        $pro = \App\Models\PricingPlan::create([
            'name' => 'Pro',
            'price' => 'Professional',
            'price_subtext' => '/contact',
            'button_text' => 'Mulai Uji Coba',
            'button_link' => '#',
            'is_featured' => true,
            'badge_text' => 'MOST POPULAR',
            'order' => 2,
        ]);

        $pro->features()->createMany([
            ['name' => 'Semua fitur Basic', 'is_highlighted' => true, 'order' => 1],
            ['name' => 'Stok Opname (Adjustment)', 'order' => 2],
            ['name' => 'Database Customer Terintegrasi', 'order' => 3],
            ['name' => 'Penerimaan Piutang', 'order' => 4],
            ['name' => 'Dashboard Analisis Konsumen', 'order' => 5],
        ]);

        $enterprise = \App\Models\PricingPlan::create([
            'name' => 'Enterprise',
            'price' => 'Custom Pack',
            'price_subtext' => '/consult',
            'button_text' => 'Hubungi Kami',
            'button_link' => '#',
            'is_featured' => false,
            'order' => 3,
        ]);

        $enterprise->features()->createMany([
            ['name' => 'Semua fitur Pro', 'is_highlighted' => true, 'order' => 1],
            ['name' => 'Buku Besar (Ledger)', 'order' => 2],
            ['name' => 'Pencatatan Biaya & Operasional', 'order' => 3],
            ['name' => 'Sistem Otorisasi Owner', 'order' => 4],
        ]);
    }
}
