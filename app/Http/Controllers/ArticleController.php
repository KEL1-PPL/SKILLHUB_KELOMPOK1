<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $articles = Article::with('user')->latest()->get();
        } elseif ($user->role === 'mentor') {
            $articles = Article::where('user_id', $user->id)->latest()->get();
        } else {
            $articles = Article::where('status', 'approved')->latest()->get();
        }

        return view('features.article.index', [
            'articles' => $articles,
            'title' => 'Articles'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role !== 'mentor') {
            return redirect()->route('articles.index')->with('error', 'Unauthorized access');
        }
        return view('features.article.create', [
            'title' => 'Create Article'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'mentor') {
            return redirect()->route('articles.index')->with('error', 'Unauthorized access');
        }

        $request->validate([
            'title' => 'required|min:3|max:255',
            'content' => 'required|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
            $data['image'] = $imagePath;
        }

        Article::create($data);

        return redirect()->route('articles.index')->with('success', 'Article created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        if (Auth::user()->role === 'student' && $article->status !== 'approved') {
            return redirect()->route('articles.index')->with('error', 'Article not available');
        }

        if (Auth::user()->role === 'mentor' && $article->user_id !== Auth::id()) {
            return redirect()->route('articles.index')->with('error', 'Unauthorized access');
        }

        return view('features.article.show', [
            'article' => $article,
            'title' => $article->title
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        if (Auth::user()->role !== 'mentor' || $article->user_id !== Auth::id()) {
            return redirect()->route('articles.index')->with('error', 'Unauthorized access');
        }

        return view('features.article.edit', [
            'article' => $article,
            'title' => 'Edit Article'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        if (Auth::user()->role !== 'mentor' || $article->user_id !== Auth::id()) {
            return redirect()->route('articles.index')->with('error', 'Unauthorized access');
        }

        $request->validate([
            'title' => 'required|min:3|max:255',
            'content' => 'required|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['status'] = 'pending'; // Reset status when article is updated

        if ($request->hasFile('image')) {
            // Delete old image
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $imagePath = $request->file('image')->store('articles', 'public');
            $data['image'] = $imagePath;
        }

        $article->update($data);

        return redirect()->route('articles.index')->with('success', 'Article updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        if (Auth::user()->role !== 'mentor' || $article->user_id !== Auth::id()) {
            return redirect()->route('articles.index')->with('error', 'Unauthorized access');
        }

        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Article deleted successfully');
    }

    public function approve(Article $article)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('articles.index')->with('error', 'Unauthorized access');
        }

        $article->update(['status' => 'approved']);

        return redirect()->route('articles.index')->with('success', 'Article approved successfully');
    }

    public function reject(Request $request, Article $article)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('articles.index')->with('error', 'Unauthorized access');
        }

        $request->validate([
            'rejected_note' => 'required|min:3'
        ]);

        $article->update([
            'status' => 'rejected',
            'rejected_note' => $request->rejected_note
        ]);

        return redirect()->route('articles.index')->with('success', 'Article rejected successfully');
    }
}
