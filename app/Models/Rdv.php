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

   // In Rdv.php model
public function patient()
{
    return $this->belongsTo(Patient::class);
}

}
