<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data Retention (Jumlah Tahun Retensi Temuan)
    |--------------------------------------------------------------------------
    |
    | Menentukan berapa tahun data temuan disimpan sebelum dihapus secara
    | otomatis oleh scheduled command (prp:prune-temuan).
    |
    */
    'retention_years' => (int) env('TEMUAN_RETENTION_YEARS', 2),
];
