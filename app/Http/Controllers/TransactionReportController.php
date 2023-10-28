<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

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
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count');

        $labels = $users->keys();
        $data = $users->values();

        return view('pages.master.report.index', compact('labels', 'data'));
    }
}
