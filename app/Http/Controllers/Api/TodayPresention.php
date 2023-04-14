<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodayPresention extends Controller
{
    public function getTodayPresention(Request $request)
    {
        $bulanIni = date('m'); // mendapatkan bulan saat ini dalam format 2 digit angka
        $nik = $request->nik;

        $presents = DB::select("call todays_presention(?)", [$request->nik]);
        $count = DB::select("SELECT COUNT(*) as count FROM presensis WHERE month(tanggal) = ? and nik = ?", [$bulanIni, $nik]);
        $response = [];


        foreach ($presents as $present) {
            $response[] = [
                'id' => $present->id,
                'presentionId' => $present->id_presensi,
                'nik' => $present->nik,
                'type' => $present->jenis,
                'time' => $present->jam
            ];
        }

        return $this->sendResponse($response, 'success');
    }
    public function presentCount(Request $request)
    {
        $bulanIni = date('m'); // mendapatkan bulan saat ini dalam format 2 digit angka
        $nik = $request->nik;

        $result = DB::select("SELECT COUNT(*) as count FROM presensis WHERE month(tanggal) = ? and nik = ?", [$bulanIni, $nik]);

        if (!count($request->all())) {
            return $this->sendError($result, 'No Data, input your ID');
        }

        return $this->sendResponse($result[0]->count, 'Success');
    }
}