<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visite extends Model
{
    use HasFactory;
    protected $fillable = ['id_rdv', 'observation'];

    public function images()
    {
        return $this->hasMany(VisiteImage::class);
    }
    
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

}
