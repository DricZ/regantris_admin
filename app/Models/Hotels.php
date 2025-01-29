<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotels extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $table = 'hotels';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];
    //

    /**
     * Get all of the transactions for the Hotels
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transactions::class, 'hotel_id', 'id');
    }

    public function users(): HasMany
    {
        return $this->HasMany(User::class, 'hotel_id', 'id');
    }
}
