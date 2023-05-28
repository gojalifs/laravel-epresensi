<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IzinKeluarRequest;
use App\Http\Resources\IzinKeluarResource;
use App\Models\IzinKeluar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IzinKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = IzinKeluar::all();
        $result = IzinKeluarResource::collection($data);
        return $this->sendResponse($result, 'Success get Data');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IzinKeluarRequest $request)
    {
        $data = new IzinKeluarResource(IzinKeluar::create($request->validated()));
        return $this->sendResponse($data, 'success');
    }

    public function show(int $nik)
    {
        // Query untuk mengambil seluruh data izin keluar berdasarkan user_nik tertentu
        $izins = DB::table('izin_keluars')->where('user_nik', '=', $nik)
            ->get()->toArray();

        // // Jika tidak ada izin keluar dengan user_nik tersebut, maka tampilkan pesan error 404
        // if (!$izins) {
        //     abort(404, 'Izin Keluar Not Found');
        // }

        // Membuat array untuk menyimpan setiap izin keluar
        $result = [];

        // Iterasi setiap izin keluar yang telah diambil
        foreach ($izins as $izin) {
            $izin_id = $izin->id;
            // Jika izin keluar dengan id tersebut belum ada pada array $result, maka tambahkan ke dalam array
            if (!array_key_exists($izin_id, $result)) {
                // Membuat instance dari IzinKeluarResource yang digunakan untuk mengatur bagaimana data izin keluar ditampilkan
                $result[$izin_id] = new IzinKeluarResource($izin);
            }
        }

        // Mengambil nilai-nilai dari array $result dan mengembalikannya dalam bentuk response
        $data = array_values($result);
        return $this->sendResponse($data, 'Izin Keluar found');


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IzinKeluar $izinKeluar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IzinKeluar $izinKeluar)
    {
        //
    }
}