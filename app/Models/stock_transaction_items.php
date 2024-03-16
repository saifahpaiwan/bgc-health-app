<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class stock_transaction_items extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'product_id',
        'product_items_id',
        'quantity_changed',
        'unit_position',
        'unit_id',
        'expires_at'
    ]; 
 
    public function rProduct(): HasOne
    {
        return $this->hasOne(products::class, 'id', 'product_id');
    }

    public function rUnits(): HasOne
    {
        return $this->hasOne(units::class, 'id', 'unit_id');
    }
}
