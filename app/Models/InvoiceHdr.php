<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed|string $sale_no
 * @property int|mixed $subtotal
 * @property int|mixed $discount
 * @property int|mixed $grandtotal
 * @property int|mixed $total_qty
 * @property mixed|string $payment
 * @property mixed $cash_amount
 * @property mixed $change_amount
 * @property mixed|string $status
 */
class InvoiceHdr extends Model
{
    use HasFactory;

    protected $table = 'sale_invoice_hdr';

    protected $fillable = [
        'sale_no',
        'subtotal',
        'discount',
        'grandtotal',
        'total_qty',
        'payment',
        'cash_amount',
        'change_amount',
        'status',
    ];

    public function getRouteKeyName(): string
    {
        return 'sale_no';
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class, 'hdr_id', 'id');
    }
}
