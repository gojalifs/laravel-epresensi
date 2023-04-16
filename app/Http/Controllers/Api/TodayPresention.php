<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodayPresention extends Controller
{

    public function getTodayPresention(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $date = date('d-m-Y', time());


        /// check if the request is used for getting report
        if ($request->date) {
            $date = $request->date;
            $presentsQuery = DB::select("call todays_presention(?, ?)", [
                $request->nik,
                $request->date
            ]);
        } else {
            $presentsQuery = DB::select("call todays_presention(?, ?)", [$request->nik, NULL]);
        }

        $response = [];

        foreach ($presentsQuery as $present) {
            if (count($presentsQuery) == 2) {

                $response[] = [
                    'id' => $present->id,
                    'presentionId' => $present->id_presensi,
                    'nik' => $present->nik,
                    'type' => $present->jenis,
                    'time' => $present->jam,
                    'date' => $present->tanggal,
                    'imgPath' => $present->img_path
                ];
                continue;
            } else {
                if ($present->jenis === 'masuk') {
                    $response[] = [
                        'id' => $present->id,
                        'presentionId' => $present->id_presensi,
                        'nik' => $present->nik,
                        'type' => $present->jenis,
                        'time' => $present->jam,
                        'date' => $present->tanggal,
                        'imgPath' => $present->img_path
                    ];
                    $response[] = [
                        'id' => null,
                        'presentionId' => '',
                        'nik' => '',
                        'type' => '',
                        'time' => '',
                        'date' => '',
                        'imgPath' => ''
                    ];
                } else {
                    $response[] = [
                        'id' => null,
                        'presentionId' => '',
                        'nik' => '',
                        'type' => '',
                        'time' => '',
                        'date' => '',
                        'imgPath' => ''
                    ];
                    $response[] = [
                        'id' => $present->id,
                        'presentionId' => $present->id_presensi,
                        'nik' => $present->nik,
                        'type' => $present->jenis,
                        'time' => $present->jam,
                        'date' => $present->tanggal,
                        'imgPath' => $present->img_path
                    ];
                }
            }
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