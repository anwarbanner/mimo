<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rdv extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'title',
        'start',
        'end',
        'allDay',
        'etat',
    ];

    // Cast the start and end attributes to datetime
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    // Relationship with Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

