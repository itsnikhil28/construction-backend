<?php

namespace App\Http\Controllers;

use App\Models\services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = services::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'services' => $services
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
        $service = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'slug' => 'unique:services,slug',
                'short_desc' => 'required',
                'content' => 'required',
                'image' => 'required|image|mimes:png,jpg,webp,jpeg',
                'status' => 'required'
            ]
        );

        if ($service->fails()) {
            return response()->json([
                'status' => false,
                'error' => $service->errors()->first(),
            ], 401);
        }

        if ($request->hasFile('image')) {
            $image = $request->image;
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('services', $imagename, ['disk' => 's3', 'visibility' => 'public']);
        }

        services::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'short_desc' => $request->short_desc,
            'content' => $request->content,
            'image' => $imagename,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => true,
            'success' => 'Service Created Successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service = services::findorfail($id);

        if ($service) {
            return response()->json([
                'status' => true,
                'service' => $service,
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
        $service = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'slug' => 'unique:services,slug' . $id,
                'short_desc' => 'required',
                'content' => 'required',
                'image' => 'image|mimes:png,jpg,webp,jpeg',
                'status' => 'required'
            ]
        );

        if ($service->fails()) {
            return response()->json([
                'status' => false,
                'error' => $service->errors()->first(),
            ], 401);
        }

        $service = services::findorfail($id);

        if ($service) {
            $service->title = $request->title;
            $service->slug = Str::slug($request->title);
            $service->short_desc = $request->short_desc;
            $service->content = $request->content;
            $service->status = $request->status;

            if ($request->hasFile('image')) {
                $image = $request->image;
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('services', $imagename, ['disk' => 's3', 'visibility' => 'public']);

                if (!empty($service->image)) {
                    $oldImagePath = 'services/' . $service->image;

                    if (Storage::disk('s3')->exists($oldImagePath)) {
                        Storage::disk('s3')->delete($oldImagePath);
                    }
                }

                $service->image = $imagename;
            }

            $service->save();

            return response()->json([
                'status' => true,
                'success' => 'Service Updated Successfully',
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
        $service = services::findorfail($id);

        if ($service) {
            if (!empty($service->image)) {
                $oldImagePath = 'services/' . $service->image;

                if (Storage::disk('s3')->exists($oldImagePath)) {
                    Storage::disk('s3')->delete($oldImagePath);
                }
            }
            $service->delete();
            return response()->json([
                'status' => true,
                'success' => 'Service Deleted Successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'error' => "Can't fetch details"
            ], 401);
        }
    }
}
