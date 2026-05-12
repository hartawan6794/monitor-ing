<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Langganan DashMo</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0f172a; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #0f172a; padding: 40px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%;">
                    
                    {{-- HEADER --}}
                    <tr>
                        <td style="text-align: center; padding-bottom: 32px;">
                            <div style="display: inline-flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1, #22d3ee); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 20px; color: #fff;">📊</span>
                                </div>
                                <span style="font-size: 22px; font-weight: 700; color: #f1f5f9; letter-spacing: -0.5px;">DashMo</span>
                            </div>
                        </td>
                    </tr>

                    {{-- MAIN CARD --}}
                    <tr>
                        <td style="background: linear-gradient(145deg, rgba(30,41,59,0.95), rgba(15,23,42,0.98)); border: 1px solid rgba(99,102,241,0.2); border-radius: 24px; padding: 40px 36px;">
                            
                            {{-- Urgency Badge --}}
                            <div style="text-align: center; margin-bottom: 24px;">
                                @if($daysLeft <= 1)
                                    <span style="display: inline-block; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; font-size: 12px; font-weight: 700; padding: 6px 16px; border-radius: 100px; letter-spacing: 0.5px;">
                                        🔴 SANGAT MENDESAK
                                    </span>
                                @elseif($daysLeft <= 3)
                                    <span style="display: inline-block; background: rgba(234,179,8,0.15); border: 1px solid rgba(234,179,8,0.3); color: #fde047; font-size: 12px; font-weight: 700; padding: 6px 16px; border-radius: 100px; letter-spacing: 0.5px;">
                                        🟡 SEGERA PERPANJANG
                                    </span>
                                @else
                                    <span style="display: inline-block; background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc; font-size: 12px; font-weight: 700; padding: 6px 16px; border-radius: 100px; letter-spacing: 0.5px;">
                                        ⏰ PENGINGAT LANGGANAN
                                    </span>
                                @endif
                            </div>

                            {{-- Title --}}
                            <h1 style="color: #f1f5f9; font-size: 24px; font-weight: 700; text-align: center; margin: 0 0 8px; letter-spacing: -0.5px;">
                                Halo, {{ $subscription->user->name ?? 'Pelanggan' }}!
                            </h1>
                            <p style="color: #94a3b8; font-size: 15px; text-align: center; margin: 0 0 28px; line-height: 1.6;">
                                Langganan DashMo Anda akan berakhir dalam <strong style="color: {{ $daysLeft <= 3 ? '#fbbf24' : '#818cf8' }};">{{ $daysLeft }} hari</strong>.
                                Perpanjang sekarang agar tim Anda tetap bisa memantau penjualan tanpa gangguan.
                            </p>

                            {{-- Subscription Details --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                        <table width="100%">
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px;">Paket</td>
                                                <td style="color: #e2e8f0; font-size: 14px; font-weight: 600; text-align: right;">
                                                    {{ $subscription->pricingPlan->name ?? 'Standard' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                        <table width="100%">
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px;">Mulai Berlangganan</td>
                                                <td style="color: #e2e8f0; font-size: 14px; font-weight: 600; text-align: right;">
                                                    {{ $subscription->starts_at->translatedFormat('d F Y') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <table width="100%">
                                            <tr>
                                                <td style="color: #64748b; font-size: 13px;">Berakhir Pada</td>
                                                <td style="color: {{ $daysLeft <= 3 ? '#fbbf24' : '#818cf8' }}; font-size: 14px; font-weight: 700; text-align: right;">
                                                    {{ $subscription->expires_at->translatedFormat('d F Y') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA Button --}}
                            <div style="text-align: center; margin-bottom: 16px;">
                                <a href="https://wa.me/6285380988988?text=Halo%20tim%20DashMo,%20saya%20ingin%20memperpanjang%20langganan%20paket%20{{ urlencode($subscription->pricingPlan->name ?? 'Standard') }}%20atas%20nama%20{{ urlencode($subscription->user->name ?? '') }}."
                                   style="display: inline-block; background: linear-gradient(135deg, #6366f1, #4f46e5); color: #ffffff; text-decoration: none; font-weight: 600; font-size: 15px; padding: 14px 32px; border-radius: 14px; box-shadow: 0 4px 16px rgba(99,102,241,0.4);">
                                    🔄 Perpanjang via WhatsApp
                                </a>
                            </div>
                            <p style="color: #64748b; font-size: 12px; text-align: center; margin: 0;">
                                Atau hubungi tim kami di <strong>0853-8098-8988</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="padding: 32px 0; text-align: center;">
                            <p style="color: #475569; font-size: 12px; margin: 0 0 4px;">
                                &copy; {{ date('Y') }} DashMo System. Hak Cipta Dilindungi.
                            </p>
                            <p style="color: #334155; font-size: 11px; margin: 0;">
                                Email ini dikirim otomatis oleh sistem DashMo.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
