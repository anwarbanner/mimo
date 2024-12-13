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
    // In App\Models\Visite

    public function rdv()
    {
        return $this->belongsTo(Rdv::class, 'id_rdv', 'id'); // 'id_rdv' is the foreign key in 'visites' table, 'id' is the primary key in 'rdvs' table
    }
    public function visiteImages()
    {
        return $this->hasMany(VisiteImage::class);
    }
}
