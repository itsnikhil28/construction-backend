<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\article;
use Illuminate\Http\Request;

class articlecontroller extends Controller
{
    public function index()
    {
        $articles = article::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'articles' => $articles
        ], 200);
    }

    public function latestarticles()
    {
        $articles = article::orderBy('created_at', 'desc')->limit(3)->get();

        return response()->json([
            'status' => true,
            'articles' => $articles
        ], 200);
    }


    public function getsingleservice(string $id)
    {
        $articles = article::orderBy('created_at', 'desc')->select('title', 'image')->get();
        // dd($articles);
        $article = article::findorfail($id);

        return response()->json([
            'status' => true,
            'article' => $article,
            'articles' => $articles
        ], 200);
    }
}
