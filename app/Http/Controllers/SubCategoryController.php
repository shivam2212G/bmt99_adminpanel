<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    // List all subcategories
    public function index()
    {
        $subcategories = SubCategory::with('category')->get();
        return view('pages.subcategories.index', compact('subcategories'));
    }

    // Show create form
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('pages.subcategories.create', compact('categories'));
    }

    // Store new subcategory
    public function store(Request $request)
    {
        $request->validate([
            'sub_category_name' => 'required|string|max:255',
            'sub_category_description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'sub_category_image' => 'required|image|mimes:jpg,png,jpeg,webp,svg',
        ]);

        $imagePath = null;
        if ($request->hasFile('sub_category_image')) {
            $image = $request->file('sub_category_image');
            $imageName = time().'_'.$image->getClientOriginalName();
            if (!file_exists(public_path('subcategories'))) {
                mkdir(public_path('subcategories'), 0777, true);
            }
            $image->move(public_path('subcategories'), $imageName);
            $imagePath = 'subcategories/'.$imageName;
        }

        SubCategory::create([
            'sub_category_name' => $request->sub_category_name,
            'sub_category_description' => $request->sub_category_description,
            'sub_category_image' => $imagePath,
            'is_active' => $request->has('is_active'),
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('subcategories.index')->with('success', 'SubCategory added successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('pages.subcategories.edit', compact('subcategory', 'categories'));
    }

    // Update subcategory
    public function update(Request $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);

        $request->validate([
            'sub_category_name' => 'required|string|max:255',
            'sub_category_description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'sub_category_image' => 'nullable|image|mimes:jpg,png,jpeg,webp,svg',
        ]);

        $imagePath = $subcategory->sub_category_image;
        if ($request->hasFile('sub_category_image')) {
            if ($subcategory->sub_category_image && file_exists(public_path($subcategory->sub_category_image))) {
                unlink(public_path($subcategory->sub_category_image));
            }

            $image = $request->file('sub_category_image');
            $imageName = time().'_'.$image->getClientOriginalName();
            if (!file_exists(public_path('subcategories'))) {
                mkdir(public_path('subcategories'), 0777, true);
            }
            $image->move(public_path('subcategories'), $imageName);
            $imagePath = 'subcategories/'.$imageName;
        }

        $subcategory->update([
            'sub_category_name' => $request->sub_category_name,
            'sub_category_description' => $request->sub_category_description,
            'sub_category_image' => $imagePath,
            'is_active' => $request->has('is_active'),
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('subcategories.index')->with('success', 'SubCategory updated successfully!');
    }

    // Delete subcategory
    public function delete($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        if ($subcategory->sub_category_image && file_exists(public_path($subcategory->sub_category_image))) {
            unlink(public_path($subcategory->sub_category_image));
        }
        $subcategory->delete();
        return redirect()->route('subcategories.index')->with('success', 'SubCategory deleted successfully!');
    }

    public function getAllSubCategories()
    {
        $subcategories = SubCategory::where('is_active', true)->with('category')->get();
        return response()->json(['subcategories' => $subcategories], 200);
    }

    public function getSubCategoriesByCategory($categoryId)
    {
        $subcategories = SubCategory::where('is_active', true)
            ->where('category_id', $categoryId)
            ->with('category')
            ->get();
        return response()->json(['subcategories' => $subcategories], 200);
    }
}
