<?php

namespace App\Models;
namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use App\Models\Article;

class ArticleExploreController extends Controller
{
    public function index()
    {
        $articles = Article::where('status', 'published')->latest()->paginate(10);
        return view('all.articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        abort_if($article->status !== 'published', 404);
        return view('all.articles.show', compact('article'));
    }
}
