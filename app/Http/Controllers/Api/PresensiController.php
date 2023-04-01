<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PresensiDetailResource;
use App\Http\Resources\PresensiResource;
use App\Models\Presensi;
use App\Models\PresensiDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        // try {
            
            $nik = $request->input('nik');
            $tanggal = $request->input('tanggal');
            $jenis = $request->input('jenis');
            $jam = $request->input('jam');
            $longitude = $request->input('longitude');
            $latitude = $request->input('latitude');

            // Mendapatkan file gambar yang diupload
            $file = $request->file('img');
            $extension = $file->getClientOriginalExtension();
            // Memformat nama file
            $filename = $nik . '_' . $tanggal . '_' . $jenis . '.' . $extension;
            // Menyimpan file gambar dengan nama yang sudah diformat
            $img_path = $file->storeAs('public/img', $filename);


            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare("CALL insert_presensi(?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $nik, PDO::PARAM_INT);
            $stmt->bindParam(2, $tanggal, PDO::PARAM_STR);
            $stmt->bindParam(3, $jenis, PDO::PARAM_STR);
            $stmt->bindParam(4, $jam, PDO::PARAM_STR);
            $stmt->bindParam(5, $longitude, PDO::PARAM_STR);
            $stmt->bindParam(6, $latitude, PDO::PARAM_STR);
            $stmt->bindParam(7, $img_path, PDO::PARAM_STR);
            $stmt->execute();

            $presensi = DB::table('presensis')->where([
                ['nik', '=', $nik],
                ['tanggal', '=', $tanggal]
            ])->first();

            $presensi_details = DB::table('presensi_details')
                ->where('id_presensi', '=', $presensi->id)
                ->get()->toArray();

            $result = [];

            foreach ($presensi_details as $details) {
                $pid = $details->id;
                if (!array_key_exists($pid, $result)) {
                    $result[$pid] = new PresensiDetailResource($details);
                }
            }

            $data = new PresensiResource([
                'id' => $presensi->id,
                'nik' => $presensi->nik,
                'tanggal' => $presensi->tanggal,
                'details' => $result
            ]);
            return $this->sendResponse(array_values($result), 'success');
            return response()->json([
                'message' => 'cart added successfully',
                'status' => true
            ]);
        // } catch (Exception $e){
        //     return $this->sendError($e, 'Something error on the server');
        // }
    }



    /**
     * Display the specified resource.
     */
    public function show(Presensi $presensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Presensi $presensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presensi $presensi)
    {
        //
    }
}