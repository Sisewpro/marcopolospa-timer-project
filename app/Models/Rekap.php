<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekap extends Model
{
    use HasFactory;

    protected $fillable = ['timer_card_id', 'customer', 'therapist_name', 'time', 'status'];

    // Relasi ke TimerCard
    public function timerCard()
    {
        return $this->belongsTo(TimerCard::class);
    }
}
