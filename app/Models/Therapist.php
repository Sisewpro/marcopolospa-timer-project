<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Therapist extends Model
{
    use HasFactory;

    protected $table = 'therapists';

    protected $fillable = ['name', 'email', 'phone_number', 'status', 'availability_status'];

    // Relasi ke TimerCard
    public function timerCards()
    {
        return $this->hasMany(TimerCard::class);
    }

    // Mark therapist as non-available
    public function markAsNonAvailable()
    {
        $this->update(['availability_status' => 'non-available']);
    }

    // Cek apakah therapist tidak tersedia
    public function isUnavailable()
    {
        return $this->availability_status === 'non-available' || $this->timerCards()->where('status', '!=', 'completed')->exists();
    }
}