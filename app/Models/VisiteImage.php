<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisiteImage extends Model
{
    use HasFactory;
    protected $fillable = ['images', 'visite_id', 'description',];
    public function visite()
    {
        return $this->belongsTo(Visite::class);
    }
}
