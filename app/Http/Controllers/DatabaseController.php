<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    public function checkConnection()
    {
        try {
            DB::connection()->getPdo();
            return $this->success('Database connection is established.');
            // response()->json(['status' => 'success', 'message' => 'Database connection is established.']);
        } catch (Exception $e) {
            return $this->error('Database connection is not established.', $e->getMessage());
            // response()->json(['status' => 'error', 'message' => 'Database connection is not established.', 'error' => $e->getMessage()]);
        }
    }
}
