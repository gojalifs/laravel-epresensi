<?php
namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\IzinKeluar;
use App\Models\Ketidakhadiran;
use App\Models\RevisiAbsen;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use IntlDateFormatter;


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
        // $bulan = $request->input('bulan', date('m'));
        // $tahun = $request->input('tahun', date('Y'));
        $tanggal = $request->input('tanggal') ?? date('Y-m-d');
        $isReport = $request->isGetReport ?? false;
        // call the stored procedure to get the presensi data
        $presensis = DB::select('CALL get_presensi(?)', [$tanggal]);

        // calculate the number of days in the selected month and year
        $users = User::all();
        // $tanggal = $request->input('tanggal'); // asumsi request menggunakan metode POST
        $tanggal_array = explode('-', $tanggal);
        $bulans = (int) $tanggal_array[1];
        $tahuns = (int) $tanggal_array[0];
        // $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulans, $tahuns);

        $nik = Auth::user()->nik;
        $report = DB::select('CALL sp_laporan_bulanan(?, ?, ?)', [$nik, $bulans, $tahuns]);

        // return response()->json([
        //     'success' => true,
        //     'message' => $tanggal_array,
        // ]);
        if ($isReport) {
            return view('content.laporan_presensi', compact('presensis', 'tanggal', 'bulans', 'tahuns'))
                ->with('users', $users)
                ->with('report', $report)
                ->with('bulans', $bulans);
        } else {
            return view('content.presensi', compact('presensis', 'tanggal', 'bulans', 'tahuns'))
                ->with('users', $users)
                ->with('report', $report);

        }

    }

    public function laporanPresensi(Request $request)
    {
        $nik = $request->input('nik');

        $tanggal = $request->input('tanggal'); // asumsi request menggunakan metode POST
        $tanggal_array = explode('-', $tanggal);
        $tanggalFormatted = date('y-m-d');

        $bulan = date('m', strtotime($tanggal));

        setlocale(LC_TIME, 'id_ID'); // Mengatur locale ke Bahasa Indonesia
        $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        $formatter->setPattern('MMMM y');
        $month = $formatter->format(strtotime($tanggal));

        $bulan = $tanggal_array[1];
        $tahun = $tanggal_array[0];
        $user = User::where('nik', $nik)->first();


        $report = DB::select('CALL sp_laporan_bulanan(?, ?, ?)', [$nik, $bulan, $tahun]);

        // $leaves = Ketidakhadiran::where('nik', $nik)
        //     ->whereMonth('tanggal', $bulan)
        //     ->get();

        // $leaves = DB::select(
        //     "SELECT * FROM ketidakhadirans WHERE nik = ? AND ((tanggal <= ? AND tanggal_selesai >= ?) OR month(tanggal) = ?)",
        //     [$nik, $tanggalFormatted, $tanggalFormatted, $bulan]
        // );

        $leaves = Ketidakhadiran::where('nik', $nik)
            ->where(function ($query) use ($tanggalFormatted) {
                $query->whereDate('tanggal', '<=', $tanggalFormatted)
                    ->whereDate('tanggal_selesai', '>=', $tanggalFormatted);
            })
            ->orWhereMonth('tanggal', $bulan)
            ->get();

        foreach ($leaves as $leave) {
            if ($leave->approval_id) {
                $approval = User::where('nik', $leave->approval_id)->first()->value('nama');
                $leave->approval = $approval;
            }
            if ($leave->status == 0) {
                $leave->status = 'Belum Disetujui';
            } else if ($leave->status == 1) {
                $leave->status = 'Disetujui';
            } else {
                $leave->status = 'Ditolak';
            }
        }

        $revision = RevisiAbsen::where('user_nik', $nik)
            ->whereMonth('tanggal', $bulan)->get();

        foreach ($revision as $revise) {
            // if ($revise->approval_id) {
            //     $approval = User::where('nik', $nik)->first()->value('nama');
            //     $leave->approval = $approval;
            // }
            if ($revise->is_approved == 0) {
                $revise->is_approved = 'Belum Disetujui';
            } else if ($revise->is_approved == 1) {
                $revise->is_approved = 'Disetujui';
            } else {
                $revise->is_approved = 'Ditolak';
            }
        }

        $exits = IzinKeluar::where('user_nik', $nik)
            ->whereMonth('tanggal', $bulan)->get();

        foreach ($exits as $exit) {
            if ($exit->approval) {
                $approval = User::where('nik', $exit->approval)->first()->value('nama');
                $exit->approval = $approval;
            }
            if ($exit->is_approved == 0) {
                $exit->is_approved = 'Belum Disetujui';
            } else if ($exit->is_approved == 1) {
                $exit->is_approved = 'Disetujui';
            } else {
                $exit->is_approved = 'Ditolak';
            }
        }

        $data = [
            'users' => $user,
            'report' => $report,
            'leaves' => $leaves,
            'revisions' => $revision,
            'exits' => $exits,
            'month' => $month
        ];

        $fileName = 'laporan_presensi' . '_' . $user->nama . '_' . $user->nik . '.pdf';

        $pdf = app(PDF::class)->loadView('pdf.laporan-presensi', $data);

        // Mengubah nama file dan mengatur header untuk langsung men-download file
        return $pdf->download($fileName, ['Content-Type' => 'application/pdf', 'fileName' => $fileName]);
    }
}