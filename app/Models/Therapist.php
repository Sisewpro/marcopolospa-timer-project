<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Therapist extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'therapists';

    // The attributes that are mass assignable
    protected $fillable = ['name', 'email', 'phone_number', 'status'];
}