<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\IzinKeluar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IzinKeluarController extends Controller
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
        $tanggal = $request->input('date') ?? date('Y-m-d');
        $izin_keluars = IzinKeluar::where('tanggal', '=', $tanggal)->orderByDesc('tanggal')->get();

        foreach ($izin_keluars as $item) {
            if (!empty($item->user_nik)) {
                $name = User::where('nik', $item->user_nik)->value('nama');
                $item->name = $name;
            }
            if (!empty($item->approval)) {
                $approval_name = User::where('nik', $item->approval)->value('nama');
                $item->approval_name = $approval_name;
            }
        }
        return view('content.izin-keluar', compact('tanggal'))->with('izin_keluars', $izin_keluars);
    }

    public function update(Request $request, $id)
    {
        try {
            $izin_keluar = IzinKeluar::findOrFail($id);
            $is_approved = $request->input('is_approved');

            // set is_approved to 1 if approved, set to 2 if rejected
            if ($is_approved === 'approved') {
                $izin_keluar->is_approved = 1;
            } else if ($is_approved === 'rejected') {
                $izin_keluar->is_approved = 2;
            } else {
                // return error message if input is invalid
                return response()->json(['message' => 'Invalid input'], 400);
            }

            // set approval to the name of the user who approves/rejects the request
            $nik = Auth::user()->nik;
            $izin_keluar->approval = $nik;

            $izin_keluar->save();

            $tanggal = $request->input('date') ?? date('Y-m-d');
            $izin_keluars = IzinKeluar::where('tanggal', '=', $tanggal)->orderByDesc('tanggal')->get();
            // return success message
            foreach ($izin_keluars as $item) {
                if (!empty($item->user_nik)) {
                    $name = User::where('nik', $item->user_nik)->value('nama');
                    $item->name = $name;
                }
                if (!empty($item->approval)) {
                    $approval_name = User::where('nik', $item->approval)->value('nama');
                    $item->approval_name = $approval_name;
                }
            }

        return view('content.izin-keluar', compact('tanggal'))->with('izin_keluars', $izin_keluars);

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $revisi = IzinKeluar::findOrFail($id);
            $revisi->delete();

            $tanggal = $request->input('date') ?? date('Y-m-d');
            $izin_keluars = IzinKeluar::where('tanggal', '=', $tanggal)->orderByDesc('tanggal')->get();
            
            foreach ($izin_keluars as $item) {
                if (!empty($item->user_nik)) {
                    $name = User::where('nik', $item->user_nik)->value('nama');
                    $item->name = $name;
                }
                if (!empty($item->approval)) {
                    $approval_name = User::where('nik', $item->approval)->value('nama');
                    $item->approval_name = $approval_name;
                }
            }
            // return success message
        return view('content.izin-keluar', compact('tanggal'))->with('izin_keluars', $izin_keluars);
        } catch (\Exception $e) {
            // return error message if data not found or cannot be deleted
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}