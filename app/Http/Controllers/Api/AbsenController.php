<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AbsenRequest;
use App\Http\Resources\AbsenResource;
use App\Models\Ketidakhadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Ketidakhadiran::all();
        $result = AbsenResource::collection($data);
        return $this->sendResponse($result, 'Success get Data');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AbsenRequest $request)
    {
        $data = new AbsenResource(Ketidakhadiran::create($request->validated()));
        return $this->sendResponse($data, 'Success add');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $nik, Request $request)
    {
        // Query untuk mengambil seluruh data izin keluar berdasarkan user_nik tertentu

        $month = $request->input('month');
        $absens = DB::table('ketidakhadirans')->where('nik', '=', $nik)->whereMonth('tanggal', '=', $month)
            ->get()->toArray();

        // // Jika tidak ada absensi dengan user_nik tersebut, maka tampilkan pesan error 404
        // if (!$absens) {
        //     abort(404, 'Absen Not Found');
        // }

        // Membuat array untuk menyimpan setiap izin keluar
        $result = [];

        // Iterasi setiap izin keluar yang telah diambil
        foreach ($absens as $absen) {
            $absen_id = $absen->id;
            // Jika izin keluar dengan id tersebut belum ada pada array $result, maka tambahkan ke dalam array
            if (!array_key_exists($absen_id, $result)) {
                // Membuat instance dari AbsenResource yang digunakan untuk mengatur bagaimana data izin keluar ditampilkan
                $result[$absen_id] = new AbsenResource($absen);
            }
        }
        // Mengambil nilai-nilai dari array $result dan mengembalikannya dalam bentuk response
        $data = array_values($result);
        return $this->sendResponse($data, 'Izin Keluar found');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ketidakhadiran $ketidakhadiran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ketidakhadiran $ketidakhadiran)
    {
        //
    }
}