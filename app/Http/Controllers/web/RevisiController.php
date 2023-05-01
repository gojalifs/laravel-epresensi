<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\RevisiAbsen;
use Illuminate\Http\Request;

class RevisiController extends Controller
{
    public function index()
    {
        $revisian = RevisiAbsen::all();
        return view('content.revisi')->with('revisian', $revisian);
    }

}