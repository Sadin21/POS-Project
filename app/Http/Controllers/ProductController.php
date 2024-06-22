<?php

namespace App\Http\Controllers;

use App\Imports\ImportProduct;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Nette\Utils\Random;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(): View {
        $categories = Category::get();
        return view('pages.master.product.index', compact('categories'));
    }

    public function importExcel(Request $request): RedirectResponse {
        request()->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
        Excel::import(new ImportProduct, request()->file('file'));

        try {
            return redirect()->route('product.index')->with('success', 'Data berhasil diimport');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal diimport');
        }
    }

    public function store(Request $request): mixed {
        $mode = 'store';
        $categories = Category::get();

        if ($request->getMethod() === 'GET') return view('pages.master.product.form', compact('categories', 'mode'));

        $input = $this->validate($request, [
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:255',
            'photo'         => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'buy_price'    => 'numeric',
            // 'sale_price'    => 'numeric',
            // 'qty'           => 'numeric',
            'category_id'   => 'required|numeric|exists:categories,id',
        ]);

        $input['buy_price'] = $request->file('buy_price')? $input['buy_price'] : '0';
        $input['sale_price'] = $request->file('sale_price')? $input['sale_price'] : '0';
        $input['qty'] = $request->file('qty')? $input['qty'] : '0';
        $input['available_qty'] = $request->file('qty')? $input['qty'] : '0';

        $existCode = Product::where('code', $input['code'])->first();
        if ($existCode) return redirect()->back()->with('error', 'Kode barang sudah ada');

        if ($file = $request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = Random::generate(10) . '.' . $file->extension();
            // $file->move(public_path('assets/imgs'), $fileName);
            Storage::disk('public')->putFileAs('assets/imgs', $file, $fileName);
            $input['photo'] = $fileName;
        }

        try {
            Product::create($input);
            return redirect()->route('product.index')->with('success', 'Barang berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Barang gagal ditambahkan');
        }
    }

    public function update(Request $request, string $id): View | RedirectResponse {
        $mode = 'update';
        $categories = Category::get();
        $product = Product::find($id);
        // dd($product);
        if (!$product) return redirect()->back()->with('error', 'Data tidak ditemukan');

        if ($request->getMethod() === 'GET') return view('pages.master.product.form', compact('mode', 'categories', 'product'));

        $input = $this->validate($request, [
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:255',
            'photo'         => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'buy_price'    => 'required|numeric',
            // 'sale_price'    => 'required|numeric',
            // 'qty'           => 'required|numeric',
            'category_id'   => 'required|numeric|exists:categories,id',
        ]);

        $input['buy_price'] = $request->file('buy_price')? $input['buy_price'] : '0';
        $input['sale_price'] = $request->file('sale_price')? $input['sale_price'] : '0';
        $input['qty'] = $request->file('qty')? $input['qty'] : '0';
        $input['available_qty'] = $request->file('qty')? $input['qty'] : '0';

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = Random::generate(10) . '.' . $file->extension();
            // $file->move(public_path('assets/imgs'), $fileName);
            Storage::disk('public')->putFileAs('assets/imgs', $file, $fileName);
            $input['photo'] = $fileName;
        }

        try {
            $product->fill($input)->save();
            if ($mode === 'update') return redirect()->route('product.index')->with('success', 'Data barang berhasil diperbarui');
            return redirect()->route('product.index')->with('success', 'Barang berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Barang gagal ditambahkan');
        }
    }

    public function destroy(Request $request, $id): JsonResponse{
        try {
            $product = Product::find($id);
            if (!$product) return response()->json([ 'message' => 'Data tidak ditemukan' ], 404);

            $product->delete();
            return response()->json([ 'message' => 'Data berhasil dihapus' ]);
        } catch (\Throwable $e) {
            return response()->json([ 'message' => 'Data gagal dihapus' ]);
        }
    }

    public function getDataById($code)
    {
        // dd($request->code);
        $product = Product::where('code', $code)->get();

        return response()->json($product);
    }

    public function query(Request $request): JsonResponse {
        $limit = $request->limit;
        $offset = $request->offset;
        $keyword = $request->keyword;
        $order = $request->order?? 'desc';
        $orderBy = $request->orderBy?? 'created_at';
        $name = $request->name?? 0;
        $code = $request->code?? 0;

        $product = DB::table('products')
                        ->join('categories', 'products.category_id', '=', 'categories.id')
                        ->select('products.id', 'products.name', 'products.code', 'products.photo', 'products.sale_price', 'products.qty', 'products.available_qty', 'products.buy_price', 'products.created_at', 'products.updated_at', 'categories.name as category_name')
                        ->orderBy($orderBy, $order)
                        ->where(function ($query) use ($name) {
                            if ($name) {
                                $query->where('products.name', 'LIKE', '%' . $name . '%');
                            }
                        })
                        ->orWhere(function ($query) use ($code) {
                            if ($code) {
                                $query->where('products.code', 'LIKE', '%' . $code . '%');
                            }
                        });

        if ($limit && is_numeric($limit))   $product->limit($limit);
        if ($offset && is_numeric($offset)) $product->offset($offset);
        if ($keyword) {
            $product->where(function ($u) use ($keyword) {
                $u->where('name', 'LIKE', '%'. $keyword . '%')
                ->orWhere('code', 'LIKE', '%'. $keyword . '%');
            });
        }

        return response()->json([
            'totalRecords' => $product->count(),
            'data' => $product->get(),
            'message' => 'Success'
        ], 200);
    }
}
