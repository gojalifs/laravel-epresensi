<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // jumlah total user
        $user = User::all()->count();
        // Jumlah Guru PNS
        $jumlahGuruPNS = User::whereNotNull('nipns')->count();

        // Jumlah Guru Honorer
        $jumlahGuruHonorer = User::whereNull('nipns')->count();

        $today = Carbon::now()->format('Y-m-d');

        $jumlahSudahAbsen = Presensi::where('tanggal', $today)->count();
        $jumlahBelumAbsen = User::count() - $jumlahSudahAbsen;

        $totalGuru = User::count();
        $persentaseAbsensi = ($jumlahSudahAbsen / $totalGuru) * 100;

        $grafikLabel = [];
        $grafikAbsensiMasuk = [];
        $grafikAbsensiPulang = [];

        // Menggunakan data dummy untuk grafik
        for ($i = 0; $i < 7; $i++) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            $grafikLabel[] = $tanggal;
            $grafikAbsensiMasuk[] = rand(0, 20);
            $grafikAbsensiPulang[] = rand(0, 20);
        }

        $grafikMax = max(max($grafikAbsensiMasuk), max($grafikAbsensiPulang));

        return view(
            'home',
            compact(
                'user',
                'jumlahGuruPNS',
                'jumlahGuruHonorer',
                'jumlahSudahAbsen',
                'jumlahBelumAbsen',
                'persentaseAbsensi',
                'grafikLabel',
                'grafikAbsensiMasuk',
                'grafikAbsensiPulang',
                'grafikMax'
            )
        );
    }
}