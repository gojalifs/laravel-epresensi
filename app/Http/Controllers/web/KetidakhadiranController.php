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
    public function index()
    {
        $ketidakhadiran = Ketidakhadiran::all()->sortDesc();
        $nik = Auth::user()->nik;

        foreach ($ketidakhadiran as $item) {
            if (!empty($item->approval_id)) {
                $approval_name = User::where('nik', $item->approval_id)->value('nama');
                $item->approval_name = $approval_name;
            }
        }

        return view('content.ketidakhadiran', ['ketidakhadiran' => $ketidakhadiran]);
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
            $nik = Auth::user()->nik;
            $approval_name = Auth::user()->nama;
            $ketidakhadiran->approval_id = $nik;
            $ketidakhadiran->save();

            $ketidakhadiran = Ketidakhadiran::all()->sortDesc();
            foreach ($ketidakhadiran as $item) {
                if (!empty($item->approval_id)) {
                    $approval_name = User::where('nik', $item->approval_id)->value('nama');
                    $item->approval_name = $approval_name;
                }
            }
            // return success message
            return view('content.ketidakhadiran', ['ketidakhadiran' => $ketidakhadiran, 'approval_name' => $approval_name]);

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $nik = Auth::user()->nik;

            $ketidakhadiran = Ketidakhadiran::findOrFail($id);
            $ketidakhadiran->delete();

            $ketidakhadiran = Ketidakhadiran::all()->sortDesc();
            foreach ($ketidakhadiran as $item) {
                if (!empty($item->approval_id)) {
                    $approval_name = User::where('nik', $nik)->value('nama');
                    $item->approval_name = $approval_name;
                }
            }
            // return success message
            return view('content.ketidakhadiran')->with('ketidakhadiran', $ketidakhadiran);
        } catch (\Exception $e) {
            // return error message if data not found or cannot be deleted
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}