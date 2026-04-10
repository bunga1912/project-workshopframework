<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalVendor  = Vendor::count();
        $totalMenu    = Menu::count();
        $totalPesanan = Pesanan::count();
        $totalUser    = User::count();

        return view('home', compact(
            'totalVendor',
            'totalMenu',
            'totalPesanan',
            'totalUser'
        ));
    }
}