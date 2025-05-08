<?php

namespace App\Http\Controllers;

use App\Models\article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = article::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'articles' => $articles
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
        $article = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'slug' => 'unique:articles,slug',
                'author' => 'required',
                'status' => 'required',
                'content' => 'required',
                'image' => 'required|image|mimes:png,jpg,webp,jpeg',
            ]
        );

        if ($article->fails()) {
            return response()->json([
                'status' => false,
                'error' => $article->errors()->first(),
            ], 401);
        }

        if ($request->hasFile('image')) {
            $image = $request->image;
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('articles', $imagename, ['disk' => 's3', 'visibility' => 'public']);
        }

        article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image' => $imagename,
            'status' => $request->status,
            'author' => $request->author,
        ]);

        return response()->json([
            'status' => true,
            'success' => 'Article Created Successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = article::findorfail($id);

        if ($article) {
            return response()->json([
                'status' => true,
                'article' => $article,
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
        $article = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'slug' => 'unique:articles,slug' . $id,
                'author' => 'required',
                'content' => 'required',
                'image' => 'image|mimes:png,jpg,webp,jpeg',
                'status' => 'required',
            ]
        );

        if ($article->fails()) {
            return response()->json([
                'status' => false,
                'error' => $article->errors()->first(),
            ], 401);
        }

        $article = article::findorfail($id);

        if ($article) {
            $article->title = $request->title;
            $article->slug = Str::slug($request->title);
            $article->author = $request->author;
            $article->content = $request->content;
            $article->status = $request->status;

            if ($request->hasFile('image')) {
                $image = $request->image;
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('articles', $imagename, ['disk' => 's3', 'visibility' => 'public']);

                if (!empty($article->image)) {
                    $oldImagePath = 'articles/' . $article->image;
                    if (Storage::disk('s3')->exists($oldImagePath)) {
                        Storage::disk('s3')->delete($oldImagePath);
                    }
                }

                $article->image = $imagename;
            }

            $article->save();

            return response()->json([
                'status' => true,
                'success' => 'Article Updated Successfully',
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
        $article = article::findorfail($id);

        if ($article) {
            if (!empty($article->image)) {
                $oldImagePath = 'articles/' . $article->image;
                
                if (Storage::disk('s3')->exists($oldImagePath)) {
                    Storage::disk('s3')->delete($oldImagePath);
                }
            }

            $article->delete();

            return response()->json([
                'status' => true,
                'success' => 'Article Deleted Successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'error' => "Can't fetch details"
            ], 401);
        }
    }
}
