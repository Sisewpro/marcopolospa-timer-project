<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TimerCard extends Model
{
    use HasFactory;

    protected $fillable = ['customer', 'time', 'card_name', 'status', 'user_id'];

    /**
     * Relasi dengan model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFormattedTimeAttribute()
    {
        return gmdate('H:i:s', $this->time);
    }
}
