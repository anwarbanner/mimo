<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Choix;
use App\Models\Reponse;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['texte', 'type', 'ordre', 'sexe'];

    public function choix()
    {
        return $this->hasMany(Choix::class);
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }

    public function choixEnOrdre()
    {
        return $this->choix()->orderBy('ordre');
    }

    public function estChoixMultiple()
    {
        return $this->type === 'choix_multiple';
    }
}
