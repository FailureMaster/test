<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use App\Traits\UserNotify;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Wallet;

class User extends Authenticatable
{
    use HasApiTokens, Searchable, UserNotify;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'ver_code',
        'balance',
        'kyc_data'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'kyc_data' => 'object',
        'ver_code_send_at' => 'datetime'
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::created(function (User $user) {
            $user->generateLeadCode();
        });
    }

    public function generateLeadCode()
    {
        do {
            $leadCode = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (User::where('lead_code', $leadCode)->exists());

        $this->lead_code = $leadCode;
        $this->save();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id', 'desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }
    public function owner()
    {
        return $this->belongsTo(Admin::class, 'owner_id', 'id');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }
    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn(mixed $value, array $attributes) => $attributes['firstname'] . ' ' . $attributes['lastname'],
        );
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'ref_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'ref_by');
    }

    public function activeReferrals()
    {
        return $this->hasMany(User::class, 'ref_by');
    }

    public function allReferrals()
    {
        return $this->referrals()->with('referrer');
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE)
            ->where('account_type', 'real');
        
        // ->where('ev', Status::VERIFIED)->where('sv', Status::VERIFIED)
    }

    public function scopeInactive($query)
    {
        return $query->where('status', '!=', Status::USER_ACTIVE)
            ->orWhere('account_type', '!=', 'real');

            // ->orWhere('ev', '!=', Status::VERIFIED)
            // ->orWhere('sv', '!=', Status::VERIFIED)
    }

    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified($query)
    {
        return $query->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileUnverified($query)
    {
        return $query->where('sv', Status::UNVERIFIED);
    }

    public function scopeKycUnverified($query)
    {
        return $query->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kv', Status::KYC_PENDING);
    }

    public function scopeEmailVerified($query)
    {
        return $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query)
    {
        return $query->where('sv', Status::VERIFIED);
    }
}
