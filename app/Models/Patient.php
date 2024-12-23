<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'date_naissance',
        'sexe',
        'image',

    ];

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }
    public function invoices()
{
    return $this->hasMany(Invoice::class);
}

}
