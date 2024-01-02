<?php

namespace App\Http\Controllers;

use App\Models\InvoiceHdr;
use App\Models\SaleInvoiceHdr;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// use Barryvdh\DomPDF\Facade\Pdf;

class TransactionReportController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date') ?? null;
        $endDate = $request->input('end_date') ?? null;

        $sales = InvoiceHdr::select(
            DB::raw("COUNT(*) as count"),
            DB::raw("DATE(created_at) as date")
        )
        ->where(function($query) use ($startDate) {
            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
        })
        ->where(function($query) use ($endDate) {
            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }
        })
        ->groupBy(DB::raw("DATE(created_at)"))
        ->orderBy(DB::raw("DATE(created_at)"), 'asc')
        ->get();
    
        $labels = [];
        $data = [];

        foreach ($sales as $sale) {
            $labels[] = $sale->date; 
            $data[] = $sale->count; 
        };

        // dd($labels, $data);

        return view('pages.master.report.index', compact('labels', 'data'));
    }

    public function chartData(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $sales = InvoiceHdr::select(
            DB::raw("COUNT(*) as count"),
            DB::raw("DATE(created_at) as date")
        )
        ->when($startDate, function ($query) use ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        })
        ->groupBy(DB::raw("DATE(created_at)"))
        ->orderBy(DB::raw("DATE(created_at)"), 'asc')
        ->get();

        return response()->json([
            'labels' => $sales->pluck('date'),
            'data' => $sales->pluck('count'),
            'message' => 'Success',
        ], 200);
    }

    public function generatePdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $mpdf = new \Mpdf\Mpdf();
        $data = SaleInvoiceHdr::orderBy('created_at', 'desc')
            ->where(function($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                }
            })
            ->get();
        $mpdf->WriteHTML(view('pages.master.report.pdf-view', ['data' => $data]));
        $fileName = 'laporan-transaksi-' . date('Y-m-d') . '.pdf';
        $mpdf->Output($fileName, 'D');
    }

    public function query(Request $request): JsonResponse
    {
        $limit = $request->limit;
        $offset = $request->offset;
        $keyword = $request->keyword;
        $order = $request->order ?? 'desc';
        $orderBy = $request->orderBy ?? 'sale_invoice_hdr.created_at';
        $startDate = $request->start_date?? 0;
        $endDate = $request->end_date?? 0;
        $saleNo = $request->sale_no?? 0;

        $sale = DB::table('sale_invoice_hdr')
            ->orderBy($orderBy, $order)
            ->join('sale_invoice_line', 'sale_invoice_line.hdr_id', '=', 'sale_invoice_hdr.id')
            ->join('products', 'products.id', '=', 'sale_invoice_line.product_id')
            ->select(
                'sale_invoice_hdr.*',
                'products.name as product_name',
                'products.buy_price as product_buy_price',
                'products.sale_price as product_sale_price',
                'sale_invoice_line.qty',
                'sale_invoice_line.subtotal'
            )
            ->where(function($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereDate('sale_invoice_hdr.created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('sale_invoice_hdr.created_at', '<=', $endDate);
                }
            })
            ->where(function($query) use ($saleNo) {
                if ($saleNo) {
                    $query->where('sale_invoice_hdr.sale_no', '=', $saleNo);
                }
            });

        // $sale = DB::table('sale_invoice_hdr')
        //     ->orderBy($orderBy, $order)
        //     ->where(function($query) use ($startDate, $endDate) {
        //         if ($startDate) {
        //             $query->whereDate('sale_invoice_hdr.created_at', '>=', $startDate);
        //         }
        //         if ($endDate) {
        //             $query->whereDate('sale_invoice_hdr.created_at', '<=', $endDate);
        //         }
        //     });

        $totalIncome = DB::table('sale_invoice_hdr')
            ->orderBy($orderBy, $order)
            ->join('sale_invoice_line', 'sale_invoice_line.hdr_id', '=', 'sale_invoice_hdr.id')
            ->join('products', 'products.id', '=', 'sale_invoice_line.product_id')
            ->select(
                DB::raw('(products.sale_price - products.buy_price) as net_income')
            )
            ->where(function($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereDate('sale_invoice_hdr.created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('sale_invoice_hdr.created_at', '<=', $endDate);
                }
            });

        $totalSaledQty = DB::table('sale_invoice_hdr')
            ->orderBy($orderBy, $order)
            ->select(
                DB::raw('SUM(sale_invoice_hdr.total_qty) as total_qty')
            )
            ->where(function($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereDate('sale_invoice_hdr.created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('sale_invoice_hdr.created_at', '<=', $endDate);
                }
            });

        if ($limit && is_numeric($limit)) {
            $sale->limit($limit);
        }

        if ($offset && is_numeric($offset)) {
            $sale->offset($offset);
        }

        if ($keyword) {
            $sale->where(function ($u) use ($keyword) {
                $u->where('sale_no', 'LIKE', '%' . $keyword . '%');
            });
        }

        return response()->json([
            'totalRecords' => $sale->count(),
            'data' => [
                'sale' => $sale->get(),
                'totalIncome' => $totalIncome->get()->sum('net_income'),
                'totalSaledQty' => $totalSaledQty->get()->sum('total_qty'),
            ],
            'message' => 'Success',
        ], 200);
    }
}
