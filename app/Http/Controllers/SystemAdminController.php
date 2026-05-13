<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatabaseAccessKey;
use Illuminate\Support\Facades\File;

class SystemAdminController extends Controller
{
    /**
     * Tampilkan isi laravel.log
     */
    public function systemLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        $parsedLogs = [];
        $logSize = 0;
        
        if (File::exists($logPath)) {
            $logSize = round(filesize($logPath) / 1024 / 1024, 2); // MB
            
            // Baca baris terakhir (maksimal 2000 baris agar tidak membebani browser)
            $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (count($lines) > 2000) {
                $lines = array_slice($lines, -2000);
            }
            
            $currentLog = null;
            
            foreach ($lines as $line) {
                // Regex untuk menangkap [Date] Env.Level: Message
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (.*?)\.(DEBUG|INFO|NOTICE|WARNING|ERROR|CRITICAL|ALERT|EMERGENCY): (.*)/', $line, $matches)) {
                    // Simpan log sebelumnya jika ada
                    if ($currentLog) {
                        $parsedLogs[] = $currentLog;
                    }
                    
                    // Buat array log baru
                    $currentLog = [
                        'timestamp' => $matches[1],
                        'env' => $matches[2],
                        'level' => $matches[3],
                        'message' => $matches[4],
                        'stack' => ''
                    ];
                } else if ($currentLog) {
                    // Tambahkan baris ke stack trace log saat ini
                    $currentLog['stack'] .= htmlspecialchars($line) . "\n";
                }
            }
            
            // Masukkan log terakhir
            if ($currentLog) {
                $parsedLogs[] = $currentLog;
            }
            
            // Balik urutan agar yang terbaru di atas
            $parsedLogs = array_reverse($parsedLogs);
        }

        return view('system.logs', compact('parsedLogs', 'logPath', 'logSize'));
    }

    /**
     * Kosongkan laravel.log
     */
    public function clearLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }
        return back()->with('success', 'File log berhasil dikosongkan.');
    }

    /**
     * Tampilkan isi tabel database_access_keys
     */
    public function accessKeys()
    {
        $keys = DatabaseAccessKey::with(['user', 'availableDatabase.server'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            
        return view('system.access_keys', compact('keys'));
    }
}
