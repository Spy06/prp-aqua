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
#[Fillable(['name', 'email', 'nik', 'role', 'no_whatsapp', 'password', 'it_pin', 'encrypted_password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token', 'encrypted_password'])]
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
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'encrypted_password' => 'encrypted',
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
     * Cek apakah user ini adalah PIC terdaftar di SIVERA.
     */
    public function isSiveraPicUser(): bool
    {
        if ($this->role === 'qa' || $this->role === 'superadmin') {
            return true;
        }

        // PIC dari Master Karyawan / SIVERA
        if ($this->karyawan && $this->karyawan->is_pic && $this->karyawan->status_aktif) {
            return true;
        }

        return false;
    }

    /**
     * Cek apakah user ini adalah PIC terdaftar di BOS'Q.
     */
    public function isBosqPicUser(): bool
    {
        if ($this->role === 'qa' || $this->role === 'superadmin') {
            return true;
        }

        // PIC dari BOS'Q
        if ($this->bosqSubAreas()->exists()) {
            return true;
        }

        return false;
    }

    public function isPicUser(): bool
    {
        return $this->isSiveraPicUser() || $this->isBosqPicUser();
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
     * Sub Area BOS'Q di mana user ini ditunjuk sebagai PIC Sub Area.
     */
    public function bosqSubAreas(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(BosqSubArea::class, 'bosq_sub_area_pics', 'user_id', 'sub_area_id')->withTimestamps();
    }

    /**
     * Apakah user ini berperan sebagai Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Apakah user ini berperan sebagai QA atau Super Admin.
     */
    public function isQa(): bool
    {
        return in_array($this->role, ['qa', 'superadmin'], true);
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
