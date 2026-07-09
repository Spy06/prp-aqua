<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int         $id
 * @property string|null $nik         FK ke karyawan.nik — dipakai sebagai username login
 * @property string      $role        'karyawan' atau 'qa' — dua nilai yang diizinkan
 * @property string|null $no_whatsapp Nomor WA untuk notifikasi Twilio (format: 628xxx)
 * @property string|null $name
 * @property string|null $email       Kolom ini ada tapi tidak dipakai untuk login (NIK yang dipakai)
 * @property string      $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'nik', 'role', 'no_whatsapp', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Data karyawan yang terhubung ke akun ini.
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    /**
     * Temuan yang dilaporkan oleh user ini (sebagai Pelapor).
     */
    public function temuanDilaporkan(): HasMany
    {
        return $this->hasMany(Temuan::class, 'pelapor_id');
    }

    /**
     * Temuan di mana user ini ditunjuk sebagai PIC.
     */
    public function temuanSebagaiPic(): HasMany
    {
        return $this->hasMany(Temuan::class, 'pic_id');
    }

    /**
     * Apakah user ini berperan sebagai QA.
     */
    public function isQa(): bool
    {
        return $this->role === 'qa';
    }

    /**
     * Apakah user ini berperan sebagai karyawan biasa.
     */
    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }

    /**
     * Get the user's initials (dipakai oleh komponen Flux).
     */
    public function initials(): string
    {
        $displayName = $this->name ?? $this->nik ?? 'U';
        $initials    = Str::initials($displayName, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
}
