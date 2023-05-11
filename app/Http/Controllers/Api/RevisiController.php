<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RevisiRequest;
use App\Http\Resources\RevisiResource;
use App\Models\RevisiAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevisiController extends Controller
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
    public function store(RevisiRequest $request)
    {
        $data = new RevisiResource(RevisiAbsen::create($request->validated()));
        return $this->sendResponse($data, 'revisi sukses diajukan');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $nik)
    {
        // Query untuk mengambil seluruh data izin keluar berdasarkan user_nik tertentu

        $revisis = DB::table('revisi_absens')->where('user_nik', '=', $nik)
            ->get()->toArray();

        // Jika tidak ada absensi dengan user_nik tersebut, maka tampilkan pesan error 404
        if (!$revisis) {
            abort(404, 'Absen Not Found');
        }

        // Membuat array untuk menyimpan setiap izin keluar
        $result = [];

        // Iterasi setiap izin keluar yang telah diambil
        foreach ($revisis as $revisi) {
            $revisi_id = $revisi->id;
            // Jika izin keluar dengan id tersebut belum ada pada array $result, maka tambahkan ke dalam array
            if (!array_key_exists($revisi_id, $result)) {
                // Membuat instance dari AbsenResource yang digunakan untuk mengatur bagaimana data izin keluar ditampilkan
                $result[$revisi_id] = new RevisiResource($revisi);
            }
        }
        // Mengambil nilai-nilai dari array $result dan mengembalikannya dalam bentuk response
        $data = array_values($result);
        return $this->sendResponse($data, 'Izin Keluar found');
// Query untuk mengambil seluruh data izin keluar berdasarkan user_nik tertentu

        $absens = DB::table('ketidakhadirans')->where('nik', '=', $nik)
            ->get()->toArray();

        // Jika tidak ada absensi dengan user_nik tersebut, maka tampilkan pesan error 404
        if (!$absens) {
            abort(404, 'Absen Not Found');
        }

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
    public function update(RevisiRequest $request, RevisiAbsen $revisiAbsen)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RevisiAbsen $revisiAbsen)
    {
        //
    }
}
