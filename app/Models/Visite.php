<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visite extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'id_rdv',
        'section1_timer',
        'section2_timer',
        'section3_timer',
    ];
    
}
