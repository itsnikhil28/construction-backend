<?php

namespace App\Http\Controllers;

use App\Models\member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function index()
    {
        $members = member::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'members' => $members
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
        $member = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'job_title' => 'required',
                'linkedin_url' => 'required|url',
                'image' => 'required|image|mimes:png,jpg,webp,jpeg',
                'status' => 'required',
            ]
        );

        if ($member->fails()) {
            return response()->json([
                'status' => false,
                'error' => $member->errors()->first(),
            ], 401);
        }

        if ($request->hasFile('image')) {
            $image = $request->image;
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('members', $imagename, 'public');
        }

        member::create([
            'name' => $request->name,
            'job_title' => $request->job_title,
            'linkedin_url' => $request->linkedin_url,
            'image' => $imagename,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'success' => 'Member Created Successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = member::findorfail($id);

        if ($member) {
            return response()->json([
                'status' => true,
                'member' => $member,
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
        $members = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'job_title' => 'required',
                'linkedin_url' => 'required|url',
                'image' => 'image|mimes:png,jpg,webp,jpeg',
                'status' => 'required',
            ]
        );

        if ($members->fails()) {
            return response()->json([
                'status' => false,
                'error' => $members->errors()->first(),
            ], 401);
        }

        $member = member::findorfail($id);

        if ($member) {
            $member->name = $request->name;
            $member->job_title = $request->job_title;
            $member->linkedin_url = $request->linkedin_url;
            $member->status = $request->status;

            if ($request->hasFile('image')) {
                $image = $request->image;
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('members', $imagename, 'public');

                if (!empty($member->image)) {
                    $imagepath = public_path() . '/storage/members/' . $member->image;

                    if (file_exists($imagepath)) {
                        unlink($imagepath);
                    }
                }

                $member->image = $imagename;
            }

            $member->save();

            return response()->json([
                'status' => true,
                'success' => 'Member Updated Successfully',
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
        $member = member::findorfail($id);

        if ($member) {
            if (!empty($member->image)) {
                $imagepath = public_path() . '/storage/members/' . $member->image;

                if (file_exists($imagepath)) {
                    unlink($imagepath);
                }
            }
            $member->delete();
            return response()->json([
                'status' => true,
                'success' => 'Member Deleted Successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'error' => "Can't fetch details"
            ], 401);
        }
    }
}
