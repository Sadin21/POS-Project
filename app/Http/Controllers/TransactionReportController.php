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

        return view('pages.master.report.index', compact('labels', 'data'));
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
        $orderBy = $request->orderBy ?? 'created_at';
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $sale = DB::table('sale_invoice_hdr')
            ->orderBy($orderBy, $order)
            ->where(function($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
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
            'data' => $sale->get(),
            'message' => 'Success',
        ], 200);
    }
}
