<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    // Show all categories
    public function index()
    {
        $categories = Category::all();
        return view('pages.categories.index', compact('categories'));
    }

    // Show create form
    public function create()
    {
        return view('pages.categories.create');
    }

    // Store new category
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string',
            'category_image' => 'required|image|mimes:jpg,png,jpeg,webp,svg,gif'
        ]);

        $imagePath = null;
        if ($request->hasFile('category_image')) {
            $image = $request->file('category_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('categories'), $imageName);
            $imagePath = 'categories/' . $imageName;
        }


        Category::create([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
            'category_image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }

    // Edit form
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string',
            'category_image' => 'nullable|image|mimes:jpg,png,jpeg,webp,svg',
        ]);

        $imagePath = $category->category_image;

        if ($request->hasFile('category_image')) {
            // 🧹 Delete old image if exists
            if ($category->category_image && file_exists(public_path($category->category_image))) {
                unlink(public_path($category->category_image));
            }

            // 📸 Upload new image
            $image = $request->file('category_image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            // create folder if not exists
            if (!file_exists(public_path('categories'))) {
                mkdir(public_path('categories'), 0777, true);
            }

            $image->move(public_path('categories'), $imageName);
            $imagePath = 'categories/' . $imageName;
        }

        // 📝 Update category record
        $category->update([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
            'category_image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }


    // Delete category
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        // Delete image file if exists
        if ($category->category_image && file_exists(public_path($category->category_image))) {
            unlink(public_path($category->category_image));
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }

    public function getAllCategories()
    {
        $categories = Category::where('is_active', 1)->get();
        return response()->json(['categories' => $categories], 200);
    }
}
