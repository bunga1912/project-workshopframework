<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vendors = Vendor::with('user')->get();
        return view('vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'nama_user'   => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
        ]);

        // Buat user baru dengan role vendor
        $user = User::create([
            'name'     => $request->nama_user,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'vendor'
        ]);

        // Buat vendor dan hubungkan ke user
        Vendor::create([
            'nama_vendor' => $request->nama_vendor,
            'user_id'     => $user->id,
        ]);

        return redirect()->route('vendor.index')
                         ->with('success', 'Vendor berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $vendor = Vendor::with('user')->findOrFail($id);
        return view('vendor.update', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'nama_user'   => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $vendor->user_id,
        ]);

        // Update user
        $vendor->user->update([
            'name'  => $request->nama_user,
            'email' => $request->email,
        ]);

        // Update password kalau diisi
        if ($request->filled('password')) {
            $vendor->user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        // Update vendor
        $vendor->update([
            'nama_vendor' => $request->nama_vendor,
        ]);

        return redirect()->route('vendor.index')
                         ->with('success', 'Vendor berhasil diupdate!');
    }

    public function destroy($id)
    {
        $vendor = Vendor::with('user')->findOrFail($id);

        // Hapus user sekalian
        if ($vendor->user) {
            $vendor->user->delete();
        }

        $vendor->delete();

        return redirect()->route('vendor.index')
                         ->with('success', 'Vendor berhasil dihapus!');
    }
}