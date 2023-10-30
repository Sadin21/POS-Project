<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleInvoiceHdr extends Model
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
        'status'
    ];
}
