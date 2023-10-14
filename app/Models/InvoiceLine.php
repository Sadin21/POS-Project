<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
