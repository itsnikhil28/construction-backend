<?php

namespace App\Http\Controllers;

use App\Models\testinomial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TestinomialController extends Controller
{
    public function index()
    {
        $testinomials = testinomial::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'testinomials' => $testinomials
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
        $testinomial = Validator::make(
            $request->all(),
            [
                'citation' => 'required',
                'designation' => 'required',
                'testinomial' => 'required',
                'image' => 'required|image|mimes:png,jpg,webp,jpeg',
                'status' => 'required',
            ]
        );

        if ($testinomial->fails()) {
            return response()->json([
                'status' => false,
                'error' => $testinomial->errors()->first(),
            ], 401);
        }

        if ($request->hasFile('image')) {
            $image = $request->image;
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('testinomials', $imagename, ['disk' => 's3', 'visibility' => 'public']);
        }

        testinomial::create([
            'testinomial' => $request->testinomial,
            'citation' => $request->citation,
            'designation' => $request->designation,
            'image' => $imagename,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'success' => 'Testinomial Created Successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $testinomial = testinomial::findorfail($id);

        if ($testinomial) {
            return response()->json([
                'status' => true,
                'testinomial' => $testinomial,
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
        $testinomials = Validator::make(
            $request->all(),
            [
                'testinomial' => 'required',
                'citation' => 'required',
                'designation' => 'required',
                'image' => 'image|mimes:png,jpg,webp,jpeg',
                'status' => 'required',
            ]
        );

        if ($testinomials->fails()) {
            return response()->json([
                'status' => false,
                'error' => $testinomials->errors()->first(),
            ], 401);
        }

        $testinomial = testinomial::findorfail($id);

        if ($testinomial) {
            $testinomial->testinomial = $request->testinomial;
            $testinomial->designation = $request->designation;
            $testinomial->status = $request->status;
            $testinomial->citation = $request->citation;

            if ($request->hasFile('image')) {
                $image = $request->image;
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('testinomials', $imagename, ['disk' => 's3', 'visibility' => 'public']);

                if (!empty($testinomial->image)) {
                    $oldImagePath = 'testinomials/' . $testinomial->image;

                    if (Storage::disk('s3')->exists($oldImagePath)) {
                        Storage::disk('s3')->delete($oldImagePath);
                    }
                }

                $testinomial->image = $imagename;
            }

            $testinomial->save();

            return response()->json([
                'status' => true,
                'success' => 'Testinomial Updated Successfully',
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
        $testinomial = testinomial::findorfail($id);

        if ($testinomial) {
            if (!empty($testinomial->image)) {
                $oldImagePath = 'testinomials/' . $testinomial->image;
                
                if (Storage::disk('s3')->exists($oldImagePath)) {
                    Storage::disk('s3')->delete($oldImagePath);
                }
            }
            $testinomial->delete();
            return response()->json([
                'status' => true,
                'success' => 'Testinomial Deleted Successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'error' => "Can't fetch details"
            ], 401);
        }
    }
}
