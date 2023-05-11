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
    public function index()
    {
        $izin_keluars = IzinKeluar::all()->sortDesc();
        
        foreach ($izin_keluars as $item) {
            if (!empty($item->approval)) {
                $approval_name = User::where('nik', $item->approval)->value('nama');
                $item->approval_name = $approval_name;
            }
        }
        return view('content.izin-keluar')->with('izin_keluars', $izin_keluars);
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

            $izin_keluars = IzinKeluar::all();
            // return success message
            foreach ($izin_keluars as $item) {
                if (!empty($item->approval)) {
                    $approval_name = User::where('nik', $item->approval)->value('nama');
                    $item->approval_name = $approval_name;
                }
            }
            
            return view('content.izin-keluar')->with('izin_keluars', $izin_keluars);

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $revisi = IzinKeluar::findOrFail($id);
            $revisi->delete();

            $izin_keluars = IzinKeluar::all();
            // return success message
            return view('content.izin-keluar')->with('izin_keluars', $izin_keluars);
        } catch (\Exception $e) {
            // return error message if data not found or cannot be deleted
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}