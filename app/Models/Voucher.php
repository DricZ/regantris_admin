<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Voucher extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];
    //

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }


    /**
     * Get all of the transactions for the Hotels
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voucherDetails()
    {
        return $this->hasMany(VoucherDetail::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotels::class);
    }
}
