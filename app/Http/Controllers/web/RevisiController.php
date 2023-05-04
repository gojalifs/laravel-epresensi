<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\RevisiAbsen;
use Illuminate\Http\Request;

class RevisiController extends Controller
{
    public function index()
    {
        $revisian = RevisiAbsen::orderByDesc('tanggal')->get();
        return view('content.revisi')->with('revisian', $revisian);
    }

    public function update(Request $request, $id)
    {
        try {
            $revisi = RevisiAbsen::findOrFail($id);
            $is_approved = $request->input('is_approved');

            // set is_approved to 1 if approved, set to 2 if rejected
            if ($is_approved === 'approved') {
                $revisi->is_approved = 1;
            } else if ($is_approved === 'rejected') {
                $revisi->is_approved = 2;
            } else {
                // return error message if input is invalid
                return response()->json(['message' => 'Invalid input'], 400);
            }

            $revisi->save();

            $revisian = RevisiAbsen::orderByDesc('tanggal')->get();
            // return success message
            return view('content.revisi')->with('revisian', $revisian);

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
            // return success message
            return view('content.revisi')->with('revisian', $revisian);
        } catch (\Exception $e) {
            // return error message if data not found or cannot be deleted
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}