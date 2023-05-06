<?php
namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\RevisiAbsen;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $nik = Auth::user()->nik;
        $report = DB::select('CALL sp_laporan_bulanan(?)', [$nik]);

        return view('content.presensi', compact('presensis', 'tanggal', 'bulan', 'tahun', 'jumlah_hari'))
            ->with('users', $users)
            ->with('report', $report);
    }

    public function laporanPresensi(Request $request)
    {
        $users = User::all();
        $nik = $request->input('nik');

        $report = DB::select('CALL sp_laporan_bulanan(?)', [$nik]);

        $data = [
            'report' => $report,
            'users' => $users
        ];

        $user = User::where('nik', $nik)->first();

        $fileName = 'laporan_presensi' . '_' . $user->nama . '_' . $user->nik . '.pdf';

        $pdf = app(PDF::class)->loadView('pdf.laporan-presensi', $data);

        // Mengubah nama file dan mengatur header untuk langsung men-download file
        return $pdf->download($fileName, ['Content-Type' => 'application/pdf', 'fileName' => $fileName]);
    }
}