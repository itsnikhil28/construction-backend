<?php

namespace App\Http\Controllers;

use App\Models\project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = project::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'projects' => $projects
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'slug' => 'unique:projects,slug',
                'location' => 'required',
                'construction_type' => 'required',
                'sector' => 'required',
                'status' => 'required',
                'short_desc' => 'required',
                'content' => 'required',
                'image' => 'required|image|mimes:png,jpg,webp,jpeg',
            ]
        );

        if ($project->fails()) {
            return response()->json([
                'status' => false,
                'error' => $project->errors()->first(),
            ], 401);
        }

        if ($request->hasFile('image')) {
            $image = $request->image;
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('projects', $imagename, 'public');
        }

        project::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'short_desc' => $request->short_desc,
            'content' => $request->content,
            'image' => $imagename,
            'status' => $request->status,
            'construction_type' => $request->construction_type,
            'sector' => $request->sector,
            'location' => $request->location
        ]);

        return response()->json([
            'status' => true,
            'success' => 'Project Created Successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = project::findorfail($id);

        if ($project) {
            return response()->json([
                'status' => true,
                'project' => $project,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'error' => "Can't fetch details"
            ], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // . $category->id
        $project = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'slug' => 'unique:projects,slug' . $id,
                'short_desc' => 'required',
                'content' => 'required',
                'image' => 'image|mimes:png,jpg,webp,jpeg',
                'status' => 'required',
                'construction_type' => 'required',
                'sector' => 'required',
                'location' => 'required'
            ]
        );

        if ($project->fails()) {
            return response()->json([
                'status' => false,
                'error' => $project->errors()->first(),
            ], 401);
        }

        $project = project::findorfail($id);

        if ($project) {
            $project->title = $request->title;
            $project->slug = Str::slug($request->title);
            $project->short_desc = $request->short_desc;
            $project->content = $request->content;
            $project->status = $request->status;
            $project->construction_type = $request->construction_type;
            $project->sector = $request->sector;
            $project->location = $request->location;

            if ($request->hasFile('image')) {
                $image = $request->image;
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('projects', $imagename, 'public');

                if (!empty($project->image)) {
                    $imagepath = public_path() . '/storage/projects/' . $project->image;

                    if ($imagepath) {
                        unlink($imagepath);
                    }
                }

                $project->image = $imagename;
            }

            $project->save();

            return response()->json([
                'status' => true,
                'success' => 'Project Updated Successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'error' => "Can't fetch details"
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = project::findorfail($id);

        if ($project) {
            if (!empty($project->image)) {
                $imagepath = public_path() . '/storage/projects/' . $project->image;

                if ($imagepath) {
                    unlink($imagepath);
                }
            }
            $project->delete();
            return response()->json([
                'status' => true,
                'success' => 'Project Deleted Successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'error' => "Can't fetch details"
            ], 401);
        }
    }
}
