<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    use HasFactory;
    protected $fillable = ['valeur', 'date_reponse', 'question_id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
