<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::all(); 
        return response()->json($news);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'age_restriction' => 'nullable|integer|min:9', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $news = News::create([
            'title' => $request->title,
            'content' => $request->content,
            'age_restriction' => $request->age_restriction,
        ]);

        return response()->json(['message' => 'News created successfully', 'news' => $news], 201);
    }
}
