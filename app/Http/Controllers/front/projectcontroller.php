<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\project;

class projectcontroller extends Controller
{
    public function index()
    {
        $projects = project::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'projects' => $projects
        ], 200);
    }

    public function latestprojects()
    {
        $projects = project::orderBy('created_at', 'desc')->limit(4)->get();

        return response()->json([
            'status' => true,
            'projects' => $projects
        ], 200);
    }

    public function getsingleservice(string $id)
    {
        // $projects = project::orderBy('created_at', 'desc')->select('title')->get();
        // dd($projects);
        $project = project::findorfail($id);

        return response()->json([
            'status' => true,
            'project' => $project,
            // 'projects' => $projects
        ], 200);
    }
}
