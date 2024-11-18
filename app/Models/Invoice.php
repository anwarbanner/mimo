<?php
// Invoice.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'patient_id', 'visites_id','total_amount'
    ];



    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function soins()
    {
        return $this->belongsToMany(Soin::class)->withPivot('quantity');
    }


}
