<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'patient_id', 'visite_id', 'total_amount'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function soins()
    {
        return $this->belongsToMany(Soin::class)->withPivot('quantity');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    // Add the visite relationship
    public function visite()
    {
        return $this->belongsTo(Visite::class, 'visite_id');
    }
}
