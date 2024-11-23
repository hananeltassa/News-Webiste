<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    private function validateNewsData($request)
    {
        return Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'min_age' => 'nullable|integer|min:9',
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $userAge = $user ? $user->age : null;

        if (is_null($userAge)) {
            return response()->json(['message' => 'User age is required'], 403);
        }

        $news = News::where(function ($query) use ($userAge) {
            $query->where('min_age', '<=', $userAge)
                  ->orWhereNull('min_age'); 
        })->get();

        return response()->json($news);
    }

    public function store(Request $request)
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

    public function update(Request $request, $id)
    {
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

    public function destroy($id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $news->delete();

        return response()->json(['message' => 'News deleted successfully'], 200);
    }
}
