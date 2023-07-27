<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\RevisiAbsen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevisiController extends Controller
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
        try {
            $tanggal = $request->input('date') ?? date('Y-m-d');
            $revisian = RevisiAbsen::where('tanggal', '=', $tanggal)->orderByDesc('tanggal')->get();

            foreach ($revisian as $revisi) {
                $name = User::where('nik', $revisi->user_nik)->value('nama');
                $revisi->name = $name;
                if (!empty($revisi->approval)) {
                    $approval_name = User::where('nik', $revisi->approval)->value('nama');
                    $revisi->approval_name = $approval_name;
                }
            }
            return view('content.revisi', compact('tanggal'))->with('revisian', $revisian);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $revisi = RevisiAbsen::findOrFail($id);
            $is_approved = $request->input('is_approved');
            $tanggal = $request->input('date') ?? date('Y-m-d');

            // set is_approved to 1 if approved, set to 2 if rejected
            if ($is_approved === 'approved') {
                $revisi->is_approved = 1;
            } else if ($is_approved === 'rejected') {
                $revisi->is_approved = 2;
            } else {
                // return error message if input is invalid
                return response()->json(['message' => 'Invalid input'], 400);
            }

            // set approval to the name of the user who approves/rejects the request
            $nik = Auth::user()->nik;
            $revisi->approval = $nik;

            $revisi->save();

            $revisian = RevisiAbsen::where('tanggal', '=', $tanggal)->orderByDesc('tanggal')->get();
            foreach ($revisian as $revisi) {
                $name = User::where('nik', $revisi->user_nik)->value('nama');
                $revisi->name = $name;
                if (!empty($revisi->approval)) {
                    $approval_name = User::where('nik', $revisi->approval)->value('nama');
                    $revisi->approval_name = $approval_name;
                }
            }
            // return success message
            return view('content.revisi', compact('tanggal'))->with('revisian', $revisian);

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $revisi = RevisiAbsen::findOrFail($id);
            $revisi->delete();

            $revisian = RevisiAbsen::orderByDesc('tanggal')->get();
            foreach ($revisian as $revisi) {
                $name = User::where('nik', $revisi->user_nik)->value('nama');
                $revisi->name = $name;
                if (!empty($revisi->approval)) {
                    $approval_name = User::where('nik', $revisi->approval)->value('nama');
                    $revisi->approval_name = $approval_name;
                }
            }

            // return success message
            return view('content.revisi', compact('tanggal'))->with('revisian', $revisian);
        } catch (\Exception $e) {
            // return error message if data not found or cannot be deleted
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}