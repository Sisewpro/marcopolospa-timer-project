<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TimerCard extends Model
{
    use HasFactory;

    protected $fillable = ['card_name', 'therapist_id', 'customer', 'time', 'status', 'start_time', 'end_time'];

    public function therapist()
    {
        return $this->belongsTo(Therapist::class, 'therapist_id');
    }
    /**
     * Relasi dengan model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rekaps()
    {
        return $this->hasMany(Rekap::class);
    }

    public function getFormattedTimeAttribute()
    {
        return $this->time; // Waktu sudah dalam format H:i:s
    }
}
