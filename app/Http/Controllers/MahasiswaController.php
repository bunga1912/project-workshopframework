<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Tampilkan daftar mahasiswa + form tambah.
     * Route: GET /mahasiswa
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::latest()->get();

        return view('mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Simpan mahasiswa baru ke database.
     * Route: POST /mahasiswa
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'nim'        => 'required|string|unique:mahasiswas,nim',
            'nfc_serial' => 'nullable|string|unique:mahasiswas,nfc_serial',
        ], [
            'nim.unique'        => 'NIM sudah terdaftar.',
            'nfc_serial.unique' => 'Serial NFC sudah dipakai mahasiswa lain.',
        ]);

        Mahasiswa::create([
            'nama'       => $request->nama,
            'nim'        => $request->nim,
            'nfc_serial' => $request->nfc_serial ?? null,
        ]);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa ' . $request->nama . ' berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit mahasiswa.
     * Route: GET /mahasiswa/{id}/edit
     */
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update data mahasiswa (termasuk daftarkan/update serial NFC).
     * Route: PUT /mahasiswa/{id}
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $request->validate([
            'nama'       => 'required|string|max:255',
            'nim'        => 'required|string|unique:mahasiswas,nim,' . $id,
            'nfc_serial' => 'nullable|string|unique:mahasiswas,nfc_serial,' . $id,
        ], [
            'nim.unique'        => 'NIM sudah dipakai mahasiswa lain.',
            'nfc_serial.unique' => 'Serial NFC sudah dipakai mahasiswa lain.',
        ]);

        $mahasiswa->update([
            'nama'       => $request->nama,
            'nim'        => $request->nim,
            'nfc_serial' => $request->nfc_serial ?? null,
        ]);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Daftarkan kartu NFC ke mahasiswa via scan langsung.
     * Route: POST /mahasiswa/{id}/register-nfc
     * Body JSON: { "serial_number": "04:AB:CD:EF:12:34:56" }
     */
    public function registerNfc(Request $request, $id)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:mahasiswas,nfc_serial',
        ], [
            'serial_number.unique' => 'Serial NFC ini sudah terdaftar ke mahasiswa lain.',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update(['nfc_serial' => $request->serial_number]);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Kartu NFC berhasil didaftarkan ke ' . $mahasiswa->nama,
        ]);
    }

    /**
     * Hapus mahasiswa.
     * Route: DELETE /mahasiswa/{id}
     */
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }
}