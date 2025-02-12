<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\member;
use Illuminate\Http\Request;

class membercontroller extends Controller
{
    public function index()
    {
        $members = member::orderBy('created_at', 'asc')->get();

        return response()->json([
            'status' => true,
            'members' => $members
        ], 200);
    }
}
