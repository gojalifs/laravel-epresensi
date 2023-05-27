<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RevisiRequest;
use App\Http\Resources\RevisiResource;
use App\Models\RevisiAbsen;
use App\Models\User;
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
        try {
            $revisi = RevisiAbsen::create($request->validated());

            date_default_timezone_set('Asia/Jakarta');

            $nik = $request->input('user_nik');
            $tanggal = date("Y-m-d", strtotime("today"));
            $jam = date("H:i:s");

            $name = User::where('nik', $nik)->value('nama');
            $nama = str_replace(' ', '_', $name);

            // Mendapatkan file gambar yang diupload
            $file = $request->file('img');
            $extension = $file->getClientOriginalExtension();
            // Memformat nama file
            $filename = 'revisi_' . $nik . '_' . $nama . '_' . $tanggal .
                '_' . $jam . '.' . $extension;
            // Menyimpan file gambar dengan nama yang sudah diformat
            $img_path = $file->storeAs('public/img/revisi', $filename);
            $newImgPath = str_replace('public/', '', $img_path);

            $revisi->bukti_path = $newImgPath;
            $revisi->save();

            $data = RevisiAbsen::findOrFail($revisi->id);

            return $this->sendResponse($data, 'Revisi sukses diajukan');
        } catch (\Exception $e) {
            // Tangani kesalahan
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
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