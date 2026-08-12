<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:categories,name',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        if ($request->hasFile('background_image')) {
            try {
                $uploadResult = Cloudinary::uploadApi()->upload(
                    $request->file('background_image')->getRealPath(),
                    ['folder' => 'categories']
                );
                $data['background_image'] = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                Log::error('Cloudinary upload failed (store category): ' . $e->getMessage());
                return back()->withInput()->withErrors(['background_image' => 'Image upload failed: ' . $e->getMessage()]);
            }
        }

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id,
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        if ($request->hasFile('background_image')) {
            try {
                $uploadResult = Cloudinary::uploadApi()->upload(
                    $request->file('background_image')->getRealPath(),
                    ['folder' => 'categories']
                );
                $data['background_image'] = $uploadResult['secure_url'];
            } catch (\Exception $e) {
                Log::error('Cloudinary upload failed (update category): ' . $e->getMessage());
                return back()->withInput()->withErrors(['background_image' => 'Image upload failed: ' . $e->getMessage()]);
            }
        }

        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
