<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->get();

        return view('admin.pages', compact('pages'));
    }

    public function create()
    {
        return view('admin.editPage', ['page' => new Page]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePage($request);

        Page::create([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']).'-'.Str::random(4),
            'body' => $data['body'],
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'status' => $data['status'],
        ]);

        return redirect()->route('admin.page.index')->with('success', 'Page created.');
    }

    public function edit(Page $page)
    {
        return view('admin.editPage', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $this->validatePage($request);

        $page->title = $data['title'];
        $page->body = $data['body'];
        $page->meta_title = $data['meta_title'] ?? null;
        $page->meta_description = $data['meta_description'] ?? null;
        $page->status = $data['status'];
        $page->save();

        return redirect()->route('admin.page.index')->with('success', 'Page updated.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.page.index')->with('success', 'Page deleted.');
    }

    protected function validatePage(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,published',
        ]);
    }
}
