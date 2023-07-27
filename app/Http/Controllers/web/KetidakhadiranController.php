<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Ketidakhadiran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KetidakhadiranController extends Controller
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
        $ketidakhadiran = Ketidakhadiran::where('tanggal', '=', $tanggal)->orderByDesc('tanggal')->get();
        $nik = Auth::user()->nik;


        foreach ($ketidakhadiran as $item) {
            $name = User::where('nik', $item->nik)->value('nama');
            $item->name = $name;
            if (!empty($item->approval_id)) {
                $approval_name = User::where('nik', $item->approval_id)->value('nama');
                $item->approval_name = $approval_name;
            }
        }

        return view('content.ketidakhadiran', [
            'ketidakhadiran' => $ketidakhadiran,
            'tanggal' => $tanggal,
        ]);
    }


    public function update(Request $request, $id)
    {
        try {
            $ketidakhadiran = Ketidakhadiran::findOrFail($id);
            $status = $request->input('status');

            // set status to 1 if approved, set to 2 if rejected
            if ($status === 'approved') {
                $ketidakhadiran->status = 1;
            } else if ($status === 'rejected') {
                $ketidakhadiran->status = 2;
            } else {
                // return error message if input is invalid
                return response()->json(['message' => 'Invalid input'], 400);
            }

            // set approval to the name of the user who approves/rejects the request
            $tanggal = $request->input('date') ?? date('Y-m-d');
            $nik = Auth::user()->nik;
            $approval_name = Auth::user()->nama;
            $ketidakhadiran->approval_id = $nik;
            $ketidakhadiran->save();

            $ketidakhadiran = Ketidakhadiran::where('tanggal', '=', $tanggal)->orderByDesc('tanggal')->get();
            foreach ($ketidakhadiran as $item) {
                $name = User::where('nik', $item->nik)->value('nama');
                $item->name = $name;
                if (!empty($item->approval_id)) {
                    $approval_name = User::where('nik', $item->approval_id)->value('nama');
                    $item->approval_name = $approval_name;
                }
            }
            // return success message
            return view('content.ketidakhadiran', [
                'ketidakhadiran' => $ketidakhadiran,
                'approval_name' => $approval_name,
                'tanggal' => $tanggal,
            ]);

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $nik = Auth::user()->nik;

            $ketidakhadiran = Ketidakhadiran::findOrFail($id);
            $ketidakhadiran->delete();

            $tanggal = $request->input('date') ?? date('Y-m-d');
            $ketidakhadiran = Ketidakhadiran::where('tanggal', '=', $tanggal)->orderByDesc('tanggal')->get();
            foreach ($ketidakhadiran as $item) {
                $name = User::where('nik', $item->nik)->value('nama');
                $item->name = $name;
                if (!empty($item->approval_id)) {
                    $approval_name = User::where('nik', $item->approval_id)->value('nama');
                    $item->approval_name = $approval_name;
                }
            }
            // return success message
            return view('content.ketidakhadiran', ['tanggal' => $tanggal])->with('ketidakhadiran', $ketidakhadiran);
        } catch (\Exception $e) {
            // return error message if data not found or cannot be deleted
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}