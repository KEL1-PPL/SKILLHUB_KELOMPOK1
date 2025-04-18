<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::all();
        return view('user.profile.index', compact('profiles'));
    }

    public function create()
    {
        return view('user.profile.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:profiles,email',
        ]);

        Profile::create($request->all());
        return redirect()->route('profile.index');
    }

    public function show($id)
    {
        $profile = Profile::findOrFail($id);
        return view('user.profile.show', compact('profile'));
    }

    public function edit($id)
    {
        $profile = Profile::findOrFail($id);
        return view('user.profile.edit', compact('profile'));
    }

    public function update(Request $request, $id)
    {
        $profile = Profile::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:profiles,email,' . $profile->id,
        ]);

        $profile->update($request->all());
        return redirect()->route('profile.index');
    }

    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->delete();
        return redirect()->route('profile.index');
    }
}