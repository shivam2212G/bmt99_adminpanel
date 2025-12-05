<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FeatureBrand;
use Illuminate\Http\Request;

class FeatureBrandController extends Controller
{

    public function index()
    {
        $brands = FeatureBrand::latest()->get();
        return view('pages.featurebrands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'feature_brand_name' => 'required|string|max:255',
            'feature_brand_image' => 'nullable|image|mimes:jpg,png,jpeg,webp,svg',
        ]);

        $imagePath = 'assets/img/icons/misc/leaf-red.png';
        if ($request->hasFile('feature_brand_image')) {
            $image = $request->file('feature_brand_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('brands'), $imageName);
            $imagePath = 'brands/' . $imageName;
        }

        FeatureBrand::create([
            'feature_brand_name' => $request->feature_brand_name,
            'feature_brand_image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('feature_brands.index')->with('success', 'Brand added successfully!');
    }

    public function update(Request $request, $id)
    {
        $brand = FeatureBrand::findOrFail($id);

        $request->validate([
            'feature_brand_name' => 'required|string|max:255',
            'feature_brand_image' => 'nullable|image|mimes:jpg,png,jpeg,webp,svg|max:2048',
        ]);

        $imagePath = $brand->feature_brand_image;
        if ($request->hasFile('feature_brand_image')) {
            if (file_exists(public_path($brand->feature_brand_image))) {
                unlink(public_path($brand->feature_brand_image));
            }
            $image = $request->file('feature_brand_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('brands'), $imageName);
            $imagePath = 'brands/' . $imageName;
        }

        $brand->update([
            'feature_brand_name' => $request->feature_brand_name,
            'feature_brand_image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('feature_brands.index')->with('success', 'Brand updated successfully!');
    }

    public function destroy($id)
    {
        $brand = FeatureBrand::findOrFail($id);
        if (file_exists(public_path($brand->feature_brand_image))) {
            unlink(public_path($brand->feature_brand_image));
        }
        $brand->delete();

        return redirect()->route('feature_brands.index')->with('success', 'Brand deleted successfully!');
    }

    public function getAllFeatureBrands()
    {
        $brands = FeatureBrand::where('is_active', true)->get();
        return response()->json($brands);
    }
}
