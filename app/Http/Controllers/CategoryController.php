<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class CategoryController extends Controller
{
    public function index(): View {
        $mode = 'store';
        return view('pages.master.category.index', compact('mode'));
    }

    public function store(Request $request): RedirectResponse {
        $input = $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        try {
            Category::create($input);

            return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Kategori gagal ditambahkan');
        }
    }

    public function update(Request $request, string $id) {
        $mode = 'update';

        $category = Category::find($id);
        if (!$category) return redirect()->back()->with('error', 'Category tidak ditemukan');

        if ($request->getMethod() === 'GET') return view('pages.master.category.index', compact('mode', 'category'));

        $input = $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        try {
            $category->fill($input)->save();
            return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Kategori gagal ditambahkan');
        }
    }

    public function destroy(Request $request): JsonResponse{
        $category = Category::find($request->id);
        if (!$category) return response()->json([ 'message' => 'Data tidak ditemukan' ], 404);

        $category->delete();
        return response()->json([ 'message' => 'Data berhasil dihapus' ]);
    }

    public function query(Request $request): JsonResponse {
        $limit = $request->limit;
        $offset = $request->offset;
        $keyword = $request->keyword;
        $order = $request->order?? 'desc';
        $orderBy = $request->orderBy?? 'created_at';

        $category = Category::select('id', 'name', 'created_at', 'updated_at')
            ->orderBy($orderBy, $order);

        if ($limit && is_numeric($limit))   $category->limit($limit);
        if ($offset && is_numeric($offset)) $category->offset($offset);
        if ($keyword) {
            $category->where(function ($u) use ($keyword) {
                $u->where('name', 'LIKE', '%'. $keyword . '%');
            });
        }

        return response()->json([
            'totalRecords' => $category->count(),
            'data' => $category->get(),
            'message' => 'Success'
        ], 200);
    }
}
