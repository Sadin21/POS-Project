<?php

namespace App\Http\Controllers;

use App\Models\SaleInvoiceHdr;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
// use Barryvdh\DomPDF\Facade\Pdf;

class TransactionReportController extends Controller
{
    // public function index(): View {
    //     $chart_options = [
    //         'chart_title' => 'Products by months',
    //         'report_type' => 'group_by_date',
    //         'model' => 'App\Models\Product',
    //         'group_by_field' => 'created_at',
    //         'group_by_period' => 'month',
    //         'chart_type' => 'bar',
    //     ];
    //     $chart1 = new LaravelChart($chart_options);

    //     return view('pages.master.transaction.index', compact('chart1'));
    // }

    public function index(): View {
        $users = User::select(DB::raw("COUNT(*) as count"))
            ->whereYear ('created_at', date('Y'))
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count');

        $labels = $users->keys();
        $data = $users->values();

        return view('pages.master.report.index', compact('labels', 'data'));
    }

    public function downloadPage(): View {
        return view('pages.master.report.download-page');
    }

    public function generatePdf() {
        $mpdf = new \Mpdf\Mpdf();
        $data = SaleInvoiceHdr::orderBy('created_at', 'desc')->get();
        $mpdf->WriteHTML(view('pages.master.report.pdf-view', ['data' => $data]));
        $mpdf->Output('test.pdf', 'D');
    }

    public function query(Request $request): JsonResponse {
        $limit = $request->limit;
        $offset = $request->offset;
        $keyword = $request->keyword;
        $order = $request->order?? 'desc';
        $orderBy = $request->orderBy?? 'created_at';

        $sale = DB::table('sale_invoice_hdr')->orderBy($orderBy, $order);

        if ($limit && is_numeric($limit))   $sale->limit($limit);
        if ($offset && is_numeric($offset)) $sale->offset($offset);
        if ($keyword) {
            $sale->where(function ($u) use ($keyword) {
                $u->where('name', 'LIKE', '%'. $keyword . '%')
                ->orWhere('code', 'LIKE', '%'. $keyword . '%');
            });
        }

        return response()->json([
            'totalRecords' => $sale->count(),
            'data' => $sale->get(),
            'message' => 'Success'
        ], 200);
    }
}
