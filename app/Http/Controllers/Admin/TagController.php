<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('products')->orderBy('name')->get();

        return view('admin.tags', compact('tags'));
    }

    public function create()
    {
        return view('admin.editTag', ['tag' => new Tag]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);

        Tag::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::random(4),
        ]);

        return redirect()->route('admin.tag.index')->with('success', 'Tag created.');
    }

    public function edit(Tag $tag)
    {
        return view('admin.editTag', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);

        $tag->name = $data['name'];
        $tag->save();

        return redirect()->route('admin.tag.index')->with('success', 'Tag updated.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tag.index')->with('success', 'Tag deleted.');
    }
}
