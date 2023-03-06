<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_line',
        'quantity',
        'cost',
        'quantity_sold'
    ];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function delivery()
    {
        return $this->hasMany(Delivery::class);
    }
}
