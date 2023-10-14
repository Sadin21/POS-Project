<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $hdr_id
 * @property mixed $product_id
 * @property mixed $sale_price
 * @property mixed $qty
 * @property float|int|mixed $subtotal
 */
class InvoiceLine extends Model
{
    use HasFactory;

    protected $table = 'sale_invoice_line';

    protected $fillable = [
        'hdr_id',
        'product_id',
        'qty',
        'sale_price',
        'subtotal',
    ];
}
