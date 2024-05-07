<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdviserIndexRequest;
use App\Models\Adviser;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class AdviserController extends Controller

{
    public function index()
    {
        $data = Adviser::all();
        return $this->success($data);
    }

    public function show($id)
    {
        $data = Adviser::find($id);
        try {
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Adviser not found', $th], 404);
        }
    }
}
