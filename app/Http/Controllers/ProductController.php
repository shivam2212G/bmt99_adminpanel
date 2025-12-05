<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApiSettings;
use App\Models\Category;
use App\Models\FeatureBrand;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::with(['category', 'subCategory', 'featurebrand'])->get();
        return view('pages.products.index', compact('products'));
    }


    public function create()
    {
        $categories = Category::where('is_active', true)->with('subCategories')->get();
        $featureBrands = FeatureBrand::all();
        return view('pages.products.create', compact('categories', 'featureBrands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name'       => 'required|string|max:255',
            'product_description' => 'nullable',
            'product_mrp'        => 'required|numeric',
            'product_price'      => 'required|numeric',
            'product_stock'      => 'required|numeric',
            'category_id'        => 'required|exists:categories,category_id',
            'sub_category_id'    => 'required|exists:sub_categories,sub_category_id',
            'feature_brand_id'   => 'nullable|exists:feature_brands,feature_brand_id',
            'product_image'      => 'required|image|mimes:jpg,jpeg,png,webp,svg',
        ]);

        // Handle Image Upload
        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('products', $filename);
            $imagePath = 'products/' . $filename;
        }

        $discount = $request->product_mrp - $request->product_price;
        $discountPercent = ($discount / $request->product_mrp) * 100;

        Product::create([
            'product_name'       => $request->product_name,
            'product_description' => $request->product_description,
            'product_mrp'        => $request->product_mrp,
            'product_price'      => $request->product_price,
            'product_discount'   => $discountPercent ?? 0,
            'product_stock'      => $request->product_stock,
            'category_id'        => $request->category_id,
            'sub_category_id'    => $request->sub_category_id,
            'feature_brand_id'   => $request->feature_brand_id,
            'product_image'      => $imagePath,
            'is_active'          => true,
        ]);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('is_active', true)->with('subCategories')->get();
        $featureBrands = FeatureBrand::all();

        return view('pages.products.edit', compact('product', 'categories', 'featureBrands'));
    }

    // Update product
    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'product_name'       => 'required|string|max:255',
        'product_description' => 'nullable',
        'product_mrp'        => 'required|numeric',
        'product_price'      => 'required|numeric',
        'product_stock'      => 'required|numeric',
        'category_id'        => 'required|exists:categories,category_id',
        'sub_category_id'    => 'required|exists:sub_categories,sub_category_id',
        'feature_brand_id'   => 'nullable|exists:feature_brands,feature_brand_id',
        'product_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp,svg',
    ]);

    // Image upload
    $imagePath = $product->product_image; // old image

    if ($request->hasFile('product_image')) {
        // delete old file
        if ($product->product_image && file_exists(public_path($product->product_image))) {
            unlink(public_path($product->product_image));
        }

        $file = $request->file('product_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move('products', $filename);

        $imagePath = 'products/' . $filename;
    }

    $discount = $request->product_mrp - $request->product_price;
    $discountPercent = ($discount / $request->product_mrp) * 100;

    $product->update([
        'product_name'       => $request->product_name,
        'product_description' => $request->product_description,
        'product_mrp'        => $request->product_mrp,
        'product_price'      => $request->product_price,
        'product_discount'   => $discountPercent ?? 0,
        'product_stock'      => $request->product_stock,
        'category_id'        => $request->category_id,
        'sub_category_id'    => $request->sub_category_id,
        'feature_brand_id'   => $request->feature_brand_id,
        'product_image'      => $imagePath,
    ]);

    return redirect()->route('products.index')->with('success', 'Product updated successfully!');
}


    // Delete product
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        if ($product->product_image && file_exists(public_path($product->product_image))) {
            unlink(public_path($product->product_image));
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function getAllProducts()
    {
        $products = Product::with(['category', 'subCategory'])->where('is_active', true)->get();
        return response()->json($products);
    }

    public function getProductsBySubCategory($subcategoryId)
    {
        $products = Product::with(['category', 'subCategory', 'featurebrand'])
            ->where('is_active', true)
            ->where('sub_category_id', $subcategoryId)
            ->get();
        return response()->json($products);
    }
    public function getProductsByCategory($categoryId)
    {
        $products = Product::with(['category', 'subCategory', 'featurebrand'])
            ->where('is_active', true)
            ->where('category_id', $categoryId)
            ->get();
        return response()->json($products);
    }

    public function getFeaturedProducts()
    {
        $products = Product::with(['category', 'subCategory', 'featurebrand'])
            ->where('is_active', true)
            // ->where('is_featured', true)
            ->get();
        return response()->json($products);
    }

    public function getBestOffers()
    {
        $offer = ApiSettings::first()->best_offers;
        $products = Product::with(['category', 'subCategory', 'featurebrand'])
            ->where('is_active', true)
            ->where('product_discount', '>', $offer)
            ->orderBy('product_discount', 'desc')
            ->get();
        return response()->json($products);
    }

    public function getLessInStockProducts()
    {
        $stock = ApiSettings::first()->less_in_stock;
        $products = Product::with(['category', 'subCategory', 'featurebrand'])
            ->where('is_active', true)
            ->where('product_stock', '<', $stock)
            ->orderBy('product_stock', 'asc')
            ->get();
        return response()->json($products);
    }

    public function getProductsByBrandId($brand_id)
    {
        $products = Product::with(['category', 'subCategory', 'featurebrand'])
            ->where('is_active', true)
            ->where('feature_brand_id', $brand_id)
            ->get();
        return response()->json($products);
    }

    public function getNewProducts()
    {
        $last_days = ApiSettings::first()->new_products;
        $date_limit = now()->subDays($last_days)->startOfDay();

        $products = Product::with(['category', 'subCategory', 'featurebrand'])
            ->where('is_active', true)
            ->where('created_at', '>=', $date_limit)
            ->orderBy('created_at', 'desc')
            // ->take(10)
            ->get();

        return response()->json($products);
    }

    public function searchProducts(Request $request)
    {
        $query = Product::with(['category', 'subCategory', 'featurebrand'])
            ->where('is_active', true);

        // -------------------------------
        // SEARCH BY NAME OR DESCRIPTION
        // -------------------------------
        if ($request->has('query') && $request->query != '') {
            $search = $request->input('query');

            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'LIKE', "%{$search}%")
                    ->orWhere('product_description', 'LIKE', "%{$search}%");
            });
        }

        // -------------------------------
        // FILTER BY CATEGORY
        // -------------------------------
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // -------------------------------
        // FILTER BY SUBCATEGORY
        // -------------------------------
        if ($request->has('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        }

        // -------------------------------
        // FILTER BY BRAND
        // -------------------------------
        if ($request->has('brand_id')) {
            $query->where('feature_brand_id', $request->brand_id);
        }

        // -------------------------------
        // FILTER BY PRICE RANGE
        // -------------------------------
        if ($request->has('min_price')) {
            $query->where('product_price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('product_price', '<=', $request->max_price);
        }

        // -------------------------------
        // SORTING
        // -------------------------------
        if ($request->has('sort')) {
            switch ($request->sort) {

                case 'price_low_high':
                    $query->orderBy('product_price', 'ASC');
                    break;

                case 'price_high_low':
                    $query->orderBy('product_price', 'DESC');
                    break;

                case 'discount_high_low':
                    $query->orderBy('product_discount', 'DESC');
                    break;

                default:
                    // No sorting
                    break;
            }
        }

        // -------------------------------
        // FINAL RESULT
        // -------------------------------
        $products = $query->get();

        return response()->json($products);
    }
}
