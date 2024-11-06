<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choix extends Model
{
    use HasFactory;
    protected $table = 'choix';

    protected $fillable = ['texte', 'ordre', 'question_id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
