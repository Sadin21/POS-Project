<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $sale_price
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'photo',
        'sale_price',
        'qty',
        'available_qty',
        'category_id',
    ];
}
