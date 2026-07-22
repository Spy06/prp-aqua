<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Custom validation error messages for image uploads and general validation.
    |
    */

    'uploaded' => 'Gagal mengupload file. Pastikan ukuran file maksimal 3MB dan formatnya sesuai (JPG, PNG, WebP).',
    'image'    => 'File harus berupa gambar dengan format yang valid (JPG, PNG, WebP).',
    'mimes'    => 'Format file tidak sesuai. Hanya diperbolehkan mengupload gambar (JPG, PNG, WebP).',
    'max'      => [
        'numeric' => 'Nilai tidak boleh lebih dari :max.',
        'file'    => 'Ukuran file terlalu besar. Maksimal ukuran file adalah 3MB.',
        'string'  => 'Teks tidak boleh lebih dari :max karakter.',
        'array'   => 'Jumlah item tidak boleh lebih dari :max.',
    ],

    'custom' => [
        'foto_temuan' => [
            'uploaded' => 'Gagal mengupload Foto Temuan. Pastikan ukuran file maksimal 3MB dan formatnya sesuai (JPG, PNG, WebP).',
            'max'      => 'Ukuran Foto Temuan terlalu besar. Maksimal ukuran file adalah 3MB.',
            'image'    => 'File Foto Temuan harus berupa gambar (JPG, PNG, WebP).',
            'mimes'    => 'Format Foto Temuan tidak sesuai. Gunakan format JPG, PNG, atau WebP.',
        ],
        'foto_bukti' => [
            'uploaded' => 'Gagal mengupload Foto Bukti. Pastikan ukuran file maksimal 3MB dan formatnya sesuai (JPG, PNG, WebP).',
            'max'      => 'Ukuran Foto Bukti terlalu besar. Maksimal ukuran file adalah 3MB.',
            'image'    => 'File Foto Bukti harus berupa gambar (JPG, PNG, WebP).',
            'mimes'    => 'Format Foto Bukti tidak sesuai. Gunakan format JPG, PNG, atau WebP.',
        ],
    ],

    'attributes' => [
        'foto_temuan' => 'Foto Temuan',
        'foto_bukti'  => 'Foto Bukti',
    ],
];
