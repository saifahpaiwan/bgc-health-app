<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class stock_transactions extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'plant_id',
        'transaction_date',
        'transaction_type', 
        'description',
        'create_uid'
    ];  

    public function rPlant(): HasOne
    {
        return $this->hasOne(plants::class, 'id', 'plant_id');
    }

    public function rCreate(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'create_uid');
    }

    public function mStockTransactionItems(): HasMany
    {
        return $this->hasMany(stock_transaction_items::class, 'transaction_id', 'id');
    }
}
