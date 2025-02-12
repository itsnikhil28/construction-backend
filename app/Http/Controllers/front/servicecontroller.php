<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\services;
use Illuminate\Http\Request;

class servicecontroller extends Controller
{
    public function index()
    {
        $services = services::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'services' => $services
        ], 200);
    }

    public function latestservices()
    {
        $services = services::orderBy('created_at', 'desc')->limit(4)->get();

        return response()->json([
            'status' => true,
            'services' => $services
        ], 200);
    }

    public function getsingleservice(string $id)
    {
        $services = services::orderBy('created_at', 'desc')->select('title')->get();
        // dd($services);
        $service = services::findorfail($id);

        return response()->json([
            'status' => true,
            'service' => $service,
            'services' => $services
        ], 200);
    }
}
