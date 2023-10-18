<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class UserController extends Controller
{
    public function index(): View {
        return view('pages.master.user.index');
    }

    public function store(Request $request): View | RedirectResponse {
        $mode = 'store';
        $roles = Role::get();

        if ($request->getMethod() === 'GET') return view('pages.master.user.form', compact('mode','roles'));

        $input = $this->validate($request, [
            'nip'           => 'required',
            'name'          => 'required|string',
            'username'      => 'required|string',
            'address'       => 'nullable|string',
            'phone'         => 'nullable|string',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password'      => 'required|string',
            'role_id'       => 'required|exists:roles,role_id',
        ]);

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $fileName = Random::generate(10) . '.' . $file->extension();
            $file->move(public_path('assets/imgs'), $fileName);
            $input['photo'] = $fileName;
        }

        try {
            User::create($input);
            return redirect()->route('user.index')->with('success', 'Akun berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Akun gagal ditambahkan');
        }
    }

    public function update(Request $request, string $nip): View | RedirectResponse {
        $mode = 'update';
        $user = User::find($nip);
        $roles = Role::get();
        if (!$user) return redirect()->back()->with('error', 'Data tidak ditemukan');

        if ($request->getMethod() === 'GET') return view('pages.master.user.form', compact('mode', 'roles', 'user'));

        $input = $this->validate($request, [
            'nip'           => 'required',
            'name'          => 'required|string',
            'username'      => 'required|string',
            'address'       => 'nullable|string',
            'phone'         => 'nullable|string',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role_id'       => 'required|exists:roles,role_id',
            'password'      => 'nullable|string',
        ]);

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $fileName = Random::generate(10) . '.' . $file->extension();
            $file->move(public_path('assets/imgs'), $fileName);
            $input['photo'] = $fileName;
        }

        try {
            $user->fill($input)->save();
            return redirect()->route('user.index')->with('success', 'Akun berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Akun gagal ditambahkan');
        }
    }

    public function destroy(Request $request):  JsonResponse{
        $user = User::find($request->nip);
        if (!$user) return response()->json([ 'message' => 'Data tidak ditemukan' ], 404);
        
        $user->delete();
        return response()->json([ 'message' => 'Data berhasil dihapus' ]);
    }

    public function query(Request $request): JsonResponse {
        $limit = $request->limit;
        $offset = $request->offset;
        $keyword = $request->keyword;
        $order = $request->order?? 'desc';
        $orderBy = $request->orderBy?? 'created_at';

        $user = User::select('nip', 'name', 'username', 'address', 'phone', 'photo', 'role_id', 'created_at', 'updated_at')
            ->orderBy($orderBy, $order);

        if ($limit && is_numeric($limit))   $user->limit($limit);
        if ($offset && is_numeric($offset)) $user->offset($offset);
        if ($keyword) {
            $user->where(function ($u) use ($keyword) {
                $u->where('name', 'LIKE', '%'. $keyword . '%')
                ->orWhere('nip', 'LIKE', '%'. $keyword . '%')
                ->orWhere('nip', 'LIKE', '%'. $keyword . '%')
                ->orWhere('role_id', 'LIKE', '%'. $keyword . '%');
            });
        }

        return response()->json([
            'totalRecords' => $user->count(),
            'data' => $user->get(),
            'message' => 'Success'
        ], 200);
    }
}
