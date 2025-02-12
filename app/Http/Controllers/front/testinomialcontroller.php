<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\testinomial;
use Illuminate\Http\Request;

class testinomialcontroller extends Controller
{
    public function index()
    {
        $testinomials = testinomial::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'testinomials' => $testinomials
        ], 200);
    }
}
