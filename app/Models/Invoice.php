<?php
// Invoice.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'patient_id', 'total_amount', 'consultation_price'
    ];
    

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
    
    public function soins()
    {
        return $this->belongsToMany(Soin::class)->withPivot('quantity');
    }
    
  
}
