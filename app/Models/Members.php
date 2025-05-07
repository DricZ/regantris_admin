<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Members extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, LogsActivity, CanResetPassword;

    protected $table = 'members';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    protected $hidden = [
        'password','remember_token',
    ];

    /**
     * Method dari JWTSubject.
     * Mengembalikan identifier unik untuk JWT (biasanya primary key).
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Method dari JWTSubject.
     * Tambahkan claim custom jika perlu (misal role),
     * atau kembalikan array kosong.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }

    public static function getTierOptions($total){
        if($total < 20000000){
            return [
                'id' => 0,
                'name' => "Urban",
                'rate' => .05
            ];
        }elseif($total >= 20000000 && $total < 35000000){
            return [
                'id' => 1,
                'name' => "City Slicker",
                'rate' => .1
            ];
        }elseif($total >= 35000000 && $total < 40000000){
            return [
                'id' => 2,
                'name' => "Metropolis",
                'rate' => .15
            ];
        }else{
            return [
                'id' => 3,
                'name' => "Explorer",
                'rate' => .2
            ];
        }
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = (string) Str::uuid();
            }
        });
    }

    /**
     * Get all of the transactions for the Members
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transactions::class, 'member_id', 'id');
    }

    public function transactionRedeemLogs(): HasManyThrough
    {
        return $this->hasManyThrough(
            RedeemLog::class,    // Model tujuan akhir
            Transactions::class,  // Model perantara
            'member_id',         // Foreign key di tabel 'Transactionss' (Transactionss.member_id)
            'model_id',          // Foreign key di tabel 'redeem_logs' (redeem_logs.model_id)
            'id',                // Local key di tabel 'members' (members.id)
            'id'                 // Local key di tabel 'Transactionss' (Transactionss.id)
        )->where('redeem_log.model_type', Transactions::class); // Filter hanya untuk tipe Transactions
    }

    public function voucherDetailRedeemLogs(): HasManyThrough
    {
        return $this->hasManyThrough(
            RedeemLog::class,        // Model tujuan akhir
            VoucherDetail::class,    // Model perantara
            'member_id',             // Foreign key di tabel 'voucher_details' (voucher_details.member_id) - ASUMSI
            'model_id',              // Foreign key di tabel 'redeem_logs' (redeem_logs.model_id)
            'id',                    // Local key di tabel 'members' (members.id)
            'id'                     // Local key di tabel 'voucher_details' (voucher_details.id)
        )->where('redeem_log.model_type', VoucherDetail::class); // Filter hanya untuk tipe VoucherDetail
    }
}
