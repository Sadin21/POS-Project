<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $sale_price
 * @property mixed $available_qty
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'photo',
        'buy_price',
        'sale_price',
        'qty',
        'available_qty',
        'category_id',
    ];
}
