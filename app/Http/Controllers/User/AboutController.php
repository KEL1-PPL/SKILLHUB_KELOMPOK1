<?php

// ======= app/Http/Controllers/AboutController.php =======
namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $abouts = About::all();
        return view('about.index', compact('abouts'));
    }

    public function create()
    {
        return view('about.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        About::create($request->all());
        return redirect()->route('about.index');
    }

    public function show($id)
    {
        $about = About::findOrFail($id);
        return view('about.show', compact('about'));
    }

    public function edit($id)
    {
        $about = About::findOrFail($id);
        return view('about.edit', compact('about'));
    }

    public function update(Request $request, $id)
    {
        $about = About::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $about->update($request->all());
        return redirect()->route('about.index');
    }

    public function destroy($id)
    {
        $about = About::findOrFail($id);
        $about->delete();
        return redirect()->route('about.index');
    }
}