<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    // List articles 
    public function index($newsId)
    {
        $news = News::find($newsId);

        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $articles = $news->articles;
        return response()->json($articles);
    }

    // Store a new article
    public function store(Request $request, $newsId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $news = News::find($newsId);

        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $article = Article::create([
            'user_id' => Auth::id(), 
            'news_id' => $news->id,
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Article created successfully', 'article' => $article], 201);
    }
}
