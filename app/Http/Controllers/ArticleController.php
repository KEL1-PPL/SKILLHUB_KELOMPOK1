<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('features.article.index', [
            'articles' => $articles,
            'title' => 'Articles'
        ]);
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        return view('features.article.show', [
            'article' => $article,
            'title' => 'Articles'
        ]);
    }
}
