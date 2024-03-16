<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class product_items extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'product_id',
        'plant_id', 
         
        'minimum_quantity',
        'minimum_unitID',

        'unit_in_stock',
        'status',
    ];    

    public function rProducts(): HasOne
    {
        return $this->hasOne(products::class, 'id', 'product_id');
    }

    public function rPlant(): HasOne
    {
        return $this->hasOne(plants::class, 'id', 'plant_id');
    }  
    
}
 