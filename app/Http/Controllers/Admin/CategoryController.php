<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 24);
        $limit = in_array($limit, [24, 25, 50, 75, 100], true) ? $limit : 24;

        $sort = (string) $request->query('sort', 'default');
        $order = strtolower((string) $request->query('order', 'asc')) === 'desc' ? 'desc' : 'asc';

        $query = Category::query();

        switch ($sort) {
            case 'name':
                $query->orderBy('name', $order);
                break;
            case 'id':
                $query->orderBy('id', $order);
                break;
            default:
                $query->orderBy('sort_order')->orderBy('name');
        }

        $categories = $query->paginate($limit)->withQueryString();

        return view('admin.category', [
            'category' => $categories,
            'i' => ($categories->firstItem() ?? 1),
            'limit' => $limit,
            'sort' => $sort,
            'order' => $order,
        ]);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.addCategory', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'status' => 'required|in:active,disabled',
        ]);

        $category = Category::create([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::random(4),
            'is_active' => $data['status'] === 'active',
        ]);

        if ($request->hasFile('img')) {
            $filename = 'cat-'.time().'-'.Str::random(6).'.'.$request->file('img')->getClientOriginalExtension();
            $request->file('img')->move(public_path('images'), $filename);
            $category->image = $filename;
            $category->save();
        }

        return redirect()->route('admin.category.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->orderBy('name')->get();

        return view('admin.editCategory', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'status' => 'required|in:active,disabled',
        ]);

        $category->name = $data['name'];
        $category->parent_id = $data['parent_id'] ?? null;
        $category->is_active = $data['status'] === 'active';

        if ($request->hasFile('img')) {
            $filename = 'cat-'.time().'-'.Str::random(6).'.'.$request->file('img')->getClientOriginalExtension();
            $request->file('img')->move(public_path('images'), $filename);
            $category->image = $filename;
        }

        $category->save();

        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully.');
    }
}
