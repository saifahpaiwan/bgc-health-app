<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class products extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_code',
        'product_name',
        'product_images',
        'description', 
        'category_id',
        'default_price', 
        
        'quantity_per_unit', 
        'unit_id',
        'quantity_per_subunit',
        'subunit_id',

        'weight',
        'weight_unitID',

        'status',
    ];  
    
    public function rUnits(): HasOne
    {
        return $this->hasOne(units::class, 'id', 'unit_id');
    }

    public function rSubunits(): HasOne
    {
        return $this->hasOne(units::class, 'id', 'subunit_id');
    } 

    public function rWeightunits(): HasOne
    {
        return $this->hasOne(units::class, 'id', 'weight_unitID');
    } 

    public function rCategorys(): HasOne
    {
        return $this->hasOne(categcts::class, 'id', 'category_id');
    }
    

}
