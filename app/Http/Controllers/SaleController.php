<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class SaleController extends Controller
{
    public function index()
    {
        return view('pages.sale.index');
    }
}
