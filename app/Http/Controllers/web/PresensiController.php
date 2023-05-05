<?php
namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\RevisiAbsen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PresensiController extends Controller
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

    public function index(Request $request)
    {
        // get the selected month and year from request
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $tanggal = $request->input('tanggal') ?? date('Y-m-d');
        // call the stored procedure to get the presensi data
        $presensis = DB::select('CALL get_presensi(?)', [$tanggal]);

        // calculate the number of days in the selected month and year
        $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $users = User::all();

        $bulanIni = date('m'); // mendapatkan bulan saat ini dalam format 2 digit angka
        $nik = $request->nik;

        // foreach ($users as $item) {

        //     // $approval_name = User::where('nik', $nik)->value('nama');
        //     $count = DB::select("SELECT COUNT(*) as count FROM presensis WHERE month(tanggal) = ? and nik = ?", [$bulanIni, $nik]);
        //     $item->count = $count[0];

        // }
        return view('content.presensi', compact('presensis', 'tanggal', 'bulan', 'tahun', 'jumlah_hari'))->with('users', $users);
    }

}