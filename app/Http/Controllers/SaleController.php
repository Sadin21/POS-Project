<?php

namespace App\Http\Controllers;

use App\Models\InvoiceHdr;
use App\Models\InvoiceLine;
use App\Models\Product;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class SaleController extends Controller
{
    public function index()
    {
        $products = Product::query()->where('available_qty', '>', 0)->get();

        return view('pages.sale.index', compact('products'));
    }

    // public function getDataById(Request $request)
    // {
    //     $product = Product::query()->where('code', $request->code)->firstOrFail();

    //     return response()->json($product);
    // }

    public function store(Request $request)
    {
        $subtotal = 0;
        $grandTotal = 0;
        $totalQty = 0;

        foreach ($request->cart as $cart) {
            /** @var Product $product */
            $product = Product::query()->where('code', $cart['code'])->firstOrFail();
            $subtotal += $product->sale_price;
            $grandTotal += $product->sale_price * $cart['qty'];
            $totalQty += $cart['qty'];
        }

        $hdr = new InvoiceHdr();
        $hdr->sale_no = 'INV-' . date('YmdHis');
        $hdr->subtotal = $subtotal;
        $hdr->discount = 0;
        $hdr->grandtotal = $grandTotal;
        $hdr->total_qty = $totalQty;
        $hdr->payment = $request->payment;
        $hdr->cash_amount = $request->pay;
        $hdr->change_amount = $request->return;
        $hdr->status = 'paid';
        $hdr->cashier = auth()->user()->name;
        $hdr->save();

        foreach ($request->cart as $cart) {
            /** @var Product $product */
            $product = Product::query()->where('code', $cart['code'])->firstOrFail();

            $line = new InvoiceLine();
            $line->hdr_id = $hdr->id;
            $line->product_id = $product->id;
            $line->sale_price = $product->sale_price;
            $line->qty = $cart['qty'];
            $line->subtotal = $product->sale_price * $cart['qty'];
            $line->save();

            $product->update([
                'available_qty' => $product->available_qty - $cart['qty'],
            ]);
        }

        return response($hdr);
    }

    public function show(InvoiceHdr $sale = null)
    {
        if (!$sale) return redirect()->back();

        $sale->load('lines.product');

        return view('pages.sale.show', compact('sale'));
    }
}
