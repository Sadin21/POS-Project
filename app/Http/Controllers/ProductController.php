<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class ProductController extends Controller
{
    public function index(): View {
        $categories = Category::get();
        return view('pages.master.product.index', compact('categories'));
    }

    public function store(Request $request): mixed {
        $mode = 'store';
        $categories = Category::get();

        if ($request->getMethod() === 'GET') return view('pages.master.product.form', compact('categories', 'mode'));

        $input = $this->validate($request, [
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:255',
            'photo'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sale_price'    => 'required|numeric',
            'qty'           => 'required|numeric',
            'category_id'   => 'required|numeric',
        ]);

        $input['available_qty'] = $input['qty'];

        $file = $request->file('photo');
        $fileName = Random::generate(10) . '.' . $file->extension();
        $file->move(public_path('assets/imgs'), $fileName);
        $input['photo'] = $fileName;

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
            'photo'         => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sale_price'    => 'required|numeric',
            'qty'           => 'required|numeric',
            'category_id'   => 'required|numeric',
        ]);

        $input['available_qty'] = $input['qty'];

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = Random::generate(10) . '.' . $file->extension();
            $file->move(public_path('assets/imgs'), $fileName);
            $input['photo'] = $fileName;
        }

        try {
            $product->fill($input)->save();
            return redirect()->route('product.index')->with('success', 'Barang berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Barang gagal ditambahkan');
        }
    }

    public function destroy(Request $request): JsonResponse{
        $product = Product::find($request->id);
        if (!$product) return response()->json([ 'message' => 'Data tidak ditemukan' ], 404);

        $product->delete();
        return response()->json([ 'message' => 'Data berhasil dihapus' ]);
    }

    public function query(Request $request): JsonResponse {
        $limit = $request->limit;
        $offset = $request->offset;
        $keyword = $request->keyword;
        $order = $request->order?? 'desc';
        $orderBy = $request->orderBy?? 'created_at';

        $product = Product::select('id', 'name', 'code', 'photo', 'sale_price', 'qty', 'available_qty', 'category_id', 'created_at', 'updated_at')
            ->orderBy($orderBy, $order);

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
