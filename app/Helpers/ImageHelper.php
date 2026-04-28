<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Kompres gambar agar muat ke dalam kolom tipe BLOB (max 64KB)
     */
    public static function compressImageToBlob($file)
    {
        $maxWidth = 300; // Resolusi kecil agar aman < 64KB
        $maxHeight = 300;

        $imageInfo = getimagesize($file->getRealPath());
        if (!$imageInfo) {
            return file_get_contents($file->getRealPath());
        }

        list($width, $height, $type) = $imageInfo;

        // Hitung rasio
        $ratio = $width / $height;
        if ($maxWidth / $maxHeight > $ratio) {
            $newWidth = $maxHeight * $ratio;
            $newHeight = $maxHeight;
        } else {
            $newHeight = $maxWidth / $ratio;
            $newWidth = $maxWidth;
        }

        $src = null;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpeg($file->getRealPath());
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($file->getRealPath());
                break;
            case IMAGETYPE_GIF:
                $src = imagecreatefromgif($file->getRealPath());
                break;
        }

        // Jika format tidak didukung, kembalikan file aslinya (berisiko gagal jika > 64KB)
        if (!$src) {
            return file_get_contents($file->getRealPath());
        }

        $dst = imagecreatetruecolor((int) $newWidth, (int) $newHeight);

        // Handle transparansi (background putih untuk PNG/GIF)
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, (int) $newWidth, (int) $newHeight, $width, $height);

        // Tangkap output gambar ke dalam variabel memory
        ob_start();
        imagejpeg($dst, null, 70); // Kompres kualitas ke 70% (Format JPEG)
        $imageData = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        // Pengaman ekstra: Jika masih lebih dari 64KB (sangat jarang terjadi di resolusi 300x300)
        if (strlen($imageData) > 65000) {
            $src2 = imagecreatefromstring($imageData);
            ob_start();
            imagejpeg($src2, null, 40); // Turunkan kualitas ke 40%
            $imageData = ob_get_clean();
            imagedestroy($src2);
        }

        return $imageData;
    }
}
