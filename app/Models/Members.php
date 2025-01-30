<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Members extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, LogsActivity;

    protected $table = 'members';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

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
                'name' => "Metropolis",
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

    public function tier($points){

    }

}
