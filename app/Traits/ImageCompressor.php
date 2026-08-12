<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ImageCompressor
{
    /**
     * Mengompres gambar yang diunggah dan menyimpannya sebagai WebP.
     *
     * @param \Illuminate\Http\UploadedFile $file File gambar yang diunggah
     * @param string $directory Direktori penyimpanan (relatif terhadap disk public)
     * @param int $quality Kualitas kompresi WebP (0-100)
     * @return string|null Path file yang disimpan atau null jika gagal
     */
    public function compressAndSaveAsWebp($file, $directory, $quality = 80)
    {
        if (!$file) return null;

        $mime = $file->getMimeType();
        $sourcePath = $file->getRealPath();
        
        // Buat resource gambar berdasarkan mime type
        $image = null;
        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $image = @imagecreatefromjpeg($sourcePath);
        } elseif ($mime === 'image/png') {
            $image = @imagecreatefrompng($sourcePath);
            // Handle transparent background untuk PNG ke WebP
            if ($image) {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
        } elseif ($mime === 'image/webp') {
            // Jika sudah webp, simpan langsung saja
            return $file->store($directory, 'public');
        } else {
            // Fallback untuk tipe lain
            return $file->store($directory, 'public');
        }

        if (!$image) {
            // Fallback jika GD gagal meload gambar
            return $file->store($directory, 'public');
        }

        // Generate nama file baru
        $filename = Str::random(40) . '.webp';
        $fullPath = storage_path('app/public/' . $directory);

        // Pastikan direktori ada
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        $destinationPath = $fullPath . '/' . $filename;

        // Simpan sebagai WebP
        $success = imagewebp($image, $destinationPath, $quality);
        
        // Bebaskan memory
        imagedestroy($image);

        if ($success) {
            return $directory . '/' . $filename;
        }

        // Fallback
        return $file->store($directory, 'public');
    }
}
