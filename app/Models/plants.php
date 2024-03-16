<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class plants extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'plant',
        'bu',  
        'status'
    ]; 

    public function mProductItems(): HasMany
    {
        return $this->hasMany(product_items::class, 'id', 'plant_id');
    }
}
