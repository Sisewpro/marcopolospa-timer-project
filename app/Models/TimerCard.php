<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TimerCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_name', 'user_id', 'time', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

