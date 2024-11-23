<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    private function validateNewsData($request){
        return Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'min_age' => 'nullable|integer|min:9',
        ]);
    }

    public function index() //list new items
    {
        $news = News::all(); 
        return response()->json($news);
    }

    public function store(Request $request) //create new items
    {
        $validator = $this->validateNewsData($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $news = News::create([
            'title' => $request->title,
            'content' => $request->content,
            'min_age' => $request->min_age,
        ]);

        return response()->json(['message' => 'News created successfully', 'news' => $news], 201);
    }

    public function update(Request $request, $id){
        $validator = $this->validateNewsData($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $news = News::find($id);

        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $news->title = $request->title;
        $news->content = $request->content;
        $news->min_age = $request->min_age;

        $news->save();

        return response()->json(['message' => 'News updated successfully', 'news' => $news], 200);
    }
}
