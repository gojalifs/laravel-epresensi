<?php
namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\RevisiAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PresensiController extends Controller
{

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

        // pass the data to the view
        return view('content.presensi', compact('presensis', 'tanggal', 'bulan', 'tahun', 'jumlah_hari'));
    }

}