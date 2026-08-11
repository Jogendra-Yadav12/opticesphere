<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('author')->latest()->get();

        return view('admin.blogPosts', compact('posts'));
    }

    public function create()
    {
        return view('admin.editBlogPost', ['post' => new BlogPost]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePost($request);

        $post = BlogPost::create([
            'admin_id' => $request->filled('admin_id') ? $request->input('admin_id') : null,
            'title' => $data['title'],
            'slug' => Str::slug($data['title']).'-'.Str::random(4),
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        if ($request->hasFile('cover_image')) {
            $filename = 'blog-'.time().'-'.Str::random(6).'.'.$request->file('cover_image')->getClientOriginalExtension();
            $request->file('cover_image')->move(public_path('images'), $filename);
            $post->cover_image = $filename;
            $post->save();
        }

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created.');
    }

    public function edit(BlogPost $blog)
    {
        return view('admin.editBlogPost', ['post' => $blog]);
    }

    public function update(Request $request, BlogPost $blog)
    {
        $data = $this->validatePost($request);

        $blog->admin_id = $request->filled('admin_id') ? $request->input('admin_id') : null;
        $blog->title = $data['title'];
        $blog->excerpt = $data['excerpt'] ?? null;
        $blog->content = $data['content'];
        $blog->status = $data['status'];
        $blog->published_at = $data['status'] === 'published' ? ($blog->published_at ?? now()) : null;

        if ($request->hasFile('cover_image')) {
            $filename = 'blog-'.time().'-'.Str::random(6).'.'.$request->file('cover_image')->getClientOriginalExtension();
            $request->file('cover_image')->move(public_path('images'), $filename);
            $blog->cover_image = $filename;
        }

        $blog->save();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated.');
    }

    public function destroy(BlogPost $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted.');
    }

    protected function validatePost(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);
    }
}
