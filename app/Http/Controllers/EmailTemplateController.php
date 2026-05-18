<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;

class EmailTemplateController extends Controller
{
    /**
     * Display the email template edit form.
     */
    public function index()
    {
        // For now, we only manage 'subscription_expiring' template
        $templateName = 'subscription_expiring';
        
        $template = EmailTemplate::firstOrCreate(
            ['name' => $templateName],
            [
                'subject' => '⏰ Langganan DashMo Anda Akan Berakhir',
                'body' => 'Halo, [USER_NAME]!<br><br>Langganan DashMo Anda akan berakhir dalam [DAYS_LEFT] hari. Perpanjang sekarang agar tim Anda tetap bisa memantau penjualan tanpa gangguan.'
            ]
        );

        return view('system.email_templates.index', compact('template'));
    }

    /**
     * Update the specified email template.
     */
    public function update(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        $template = EmailTemplate::where('name', 'subscription_expiring')->firstOrFail();
        $template->update([
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        return redirect()->route('system.email_template.index')->with('success', 'Template email berhasil diperbarui!');
    }
}
