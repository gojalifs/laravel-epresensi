<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RevisiRequest;
use App\Http\Resources\RevisiResource;
use App\Models\RevisiAbsen;
use Illuminate\Http\Request;

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
    public function show(RevisiAbsen $revisiAbsen)
    {
        //
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
