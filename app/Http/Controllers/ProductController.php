<?php

namespace App\Http\Controllers;

use App\Imports\ImportProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\InvoiceLine;
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

        if ($request->getMethod() === 'GET') {
            return view('pages.master.product.form', compact('categories', 'mode'));
        }

        $input = $request->validate([
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:255',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'category_id'   => 'required|numeric|exists:categories,id',
        ]);

        $input['buy_price'] = $request->filled('buy_price') ? $request->input('buy_price') : 0;
        $input['sale_price'] = $request->filled('sale_price') ? $request->input('sale_price') : 0;
        $input['qty'] = $request->filled('qty') ? $request->input('qty') : 0;
        $input['available_qty'] = $request->filled('qty') ? $request->input('qty') : 0;

        $existCode = Product::where('code', $input['code'])->first();
        if ($existCode) {
            return redirect()->back()->with('error', 'Kode barang sudah ada');
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = Random::generate(10) . '.' . $file->extension();
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
        if (!$product) return redirect()->back()->with('error', 'Data tidak ditemukan');

        if ($request->getMethod() === 'GET') return view('pages.master.product.form', compact('mode', 'categories', 'product'));

        $input = $this->validate($request, [
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:255',
            'photo'         => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            // 'buy_price'    => 'required|numeric',
            // 'sale_price'    => 'required|numeric',
            // 'qty'           => 'required|numeric',
            'category_id'   => 'required|numeric|exists:categories,id',
        ]);

        $input['buy_price'] = $request->input('buy_price', $product->buy_price);
        $input['sale_price'] = $request->input('sale_price', $product->sale_price);

        if ($request->input('qty')) {
            $input['qty'] = $request->input('qty');
            $qtyDiff = (int)$request->input('qty') - (int)$product->qty;
            $input['available_qty'] = (int)$product->available_qty + $qtyDiff;
        }

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
            DB::beginTransaction();

            InvoiceLine::where('product_id', $id)->delete();

            $product = Product::findOrFail($id);
            $product->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Barang berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Barang gagal dihapus. ' . $e->getMessage()]);
            // return response()->json(['success' => false, 'message' => 'Barang gagal dihapus.']);
        }
    }

    public function getDataById($code)
    {
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
                        ->where('products.deleted_at', null)
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
