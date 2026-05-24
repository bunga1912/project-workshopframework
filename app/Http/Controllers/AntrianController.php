<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianController extends Controller
{
    public function papan()
    {
        return view('vendor.papan');
    }

    public function panggil(Request $request)
    {
        $antrian = Cache::get('antrian_data', []);

        foreach ($antrian as &$item) {
            if ($item['status'] === 'dipanggil') {
                $item['status'] = 'selesai';
            }
        }

        $dipanggil = false;
        foreach ($antrian as &$item) {
            if ($item['status'] === 'menunggu') {
                $item['status'] = 'dipanggil';
                $dipanggil = true;
                break;
            }
        }

        Cache::put('antrian_data', $antrian);

        if (!$dipanggil) {
            return back()->with('info', 'Tidak ada antrian yang menunggu.');
        }

        return back();
    }

    public function tandaiTerlambat(Request $request)
    {
        $request->validate(['nomor' => 'required|integer']);

        $antrian = Cache::get('antrian_data', []);

        foreach ($antrian as &$item) {
            if ($item['nomor'] === (int) $request->nomor && $item['status'] === 'dipanggil') {
                $item['status'] = 'terlambat';
                break;
            }
        }

        Cache::put('antrian_data', $antrian);

        return back();
    }

    public function panggilTerlambat(Request $request)
    {
        $request->validate(['nomor' => 'required|integer']);

        $antrian = Cache::get('antrian_data', []);

        foreach ($antrian as &$item) {
            if ($item['status'] === 'dipanggil') {
                $item['status'] = 'selesai';
            }
        }

        foreach ($antrian as &$item) {
            if ($item['nomor'] === (int) $request->nomor && $item['status'] === 'terlambat') {
                $item['status'] = 'dipanggil';
                break;
            }
        }

        Cache::put('antrian_data', $antrian);

        return back();
    }

    public function stream(Request $request)
    {
        return response()->stream(function () {

            set_time_limit(0);

            while (true) {
                $antrian   = Cache::get('antrian_data', []);
                $menunggu  = array_values(array_filter($antrian, fn($a) => $a['status'] === 'menunggu'));
                $terlambat = array_values(array_filter($antrian, fn($a) => $a['status'] === 'terlambat'));

                // Cari yang sedang dipanggil
                $dipanggil = collect($antrian)->firstWhere('status', 'dipanggil');

                // Kalau tidak ada yang sedang dipanggil, ambil yang terakhir selesai
                if (!$dipanggil) {
                    $dipanggil = collect($antrian)
                        ->filter(fn($a) => $a['status'] === 'selesai')
                        ->last();
                }

                $payload = [
                    'menunggu'  => $menunggu,
                    'terlambat' => $terlambat,
                    'dipanggil' => $dipanggil,
                ];

                echo 'event: queue-update' . PHP_EOL;
                echo 'data: ' . json_encode($payload) . PHP_EOL;
                echo PHP_EOL;

                ob_flush();
                flush();

                if (connection_aborted()) break;

                sleep(1);
            }

        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection'        => 'keep-alive',
        ]);
    }
}