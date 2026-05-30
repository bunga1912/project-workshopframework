<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Tampilkan daftar semua absensi.
     * Route: GET /absensi
     */
    public function index()
    {
        $absensis = Absensi::with('mahasiswa')
            ->latest('waktu_absen')
            ->get();

        return view('absensi.index', compact('absensis'));
    }

    /**
     * Tampilkan halaman scanner NFC.
     * Route: GET /scanner
     */
    public function scanner()
    {
        return view('absensi.scanner');
    }

    /**
     * Proses scan NFC — dipanggil via fetch() dari JavaScript di HP Android.
     * Route: POST /absensi/scan
     * Body JSON: { "serial_number": "04:AB:CD:EF:12:34:56" }
     */
    public function scan(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string',
        ]);

        $serial = $request->input('serial_number');

        // Cari mahasiswa berdasarkan serial NFC
        $mahasiswa = Mahasiswa::where('nfc_serial', $serial)->first();

        if (!$mahasiswa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kartu NFC tidak terdaftar. Hubungi dosen/admin.',
            ], 404);
        }

        // Cegah absen ganda di hari yang sama
        if ($mahasiswa->sudahAbsenHariIni()) {
            return response()->json([
                'status'     => 'warning',
                'message'    => $mahasiswa->nama . ' sudah absen hari ini.',
                'mahasiswa'  => $mahasiswa->nama,
                'nim'        => $mahasiswa->nim,
            ], 200);
        }

        // Tentukan status: hadir (< 08:00) atau telat (>= 08:00)
        $status = now()->hour < 8 ? 'hadir' : 'telat';

        // Simpan absensi
        Absensi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'waktu_absen'  => now(),
            'status'       => $status,
        ]);

        return response()->json([
            'status'     => 'ok',
            'message'    => 'Absensi berhasil dicatat.',
            'mahasiswa'  => $mahasiswa->nama,
            'nim'        => $mahasiswa->nim,
            'waktu'      => now()->format('H:i:s'),
            'keterangan' => $status,
        ], 201);
    }

    /**
     * Hapus data absensi.
     * Route: DELETE /absensi/{id}
     */
    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->route('absensi.index')
            ->with('success', 'Data absensi berhasil dihapus.');
    }
}