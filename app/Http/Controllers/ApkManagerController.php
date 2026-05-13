<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ApkManagerController extends Controller
{
    /**
     * Tampilkan halaman kelola APK
     */
    public function index()
    {
        $apkPath = public_path('downloads/dashmo-latest.apk');
        $apkInfo = null;

        if (File::exists($apkPath)) {
            $apkInfo = [
                'exists' => true,
                'size' => round(File::size($apkPath) / 1048576, 2), // dalam MB
                'last_modified' => Carbon::createFromTimestamp(File::lastModified($apkPath))->format('d M Y, H:i'),
                'download_url' => asset('downloads/dashmo-latest.apk')
            ];
        } else {
            $apkInfo = [
                'exists' => false,
                'size' => 0,
                'last_modified' => '-',
                'download_url' => '#'
            ];
        }

        return view('system.apk_manager', compact('apkInfo'));
    }

    /**
     * Upload dan timpa APK lama
     */
    public function upload(Request $request)
    {
        $request->validate([
            'apk_file' => 'required|file|max:102400', // Max 100MB (hanya validasi max file size)
        ], [
            'apk_file.required' => 'Pilih file APK terlebih dahulu.',
            'apk_file.file' => 'File tidak valid. Pastikan ukuran file tidak melebiber batas upload PHP (php.ini).',
            'apk_file.max' => 'Ukuran file maksimal 100MB.',
        ]);

        try {
            $file = $request->file('apk_file');
            
            // Validasi Ekstensi secara manual (lebih aman dari pada mimetypes)
            if (strtolower($file->getClientOriginalExtension()) !== 'apk') {
                return back()->with('error', 'Gagal: File harus berformat .apk');
            }

            $directory = public_path('downloads');
            
            // Buat folder jika belum ada
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Timpa file lama menjadi dashmo-latest.apk
            $request->file('apk_file')->move($directory, 'dashmo-latest.apk');

            return back()->with('success', 'File APK berhasil diunggah dan diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
        }
    }
}
