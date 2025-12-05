<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeatureBrandController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SLiderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SubCategoryController;
use Google\Service\Docs\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () { return response()->json(['message' => 'API is working']);});

Route::post('/google-login', [GoogleAuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'getAllCategories']);

Route::get('/subcategories', [SubCategoryController::class, 'getAllSubCategories']);
Route::get('/subcategories/{categoryId}', [SubCategoryController::class, 'getSubCategoriesByCategory']);

Route::get('/products', [ProductController::class, 'getAllProducts']);
Route::get('/products/{subcategoryId}', [ProductController::class, 'getProductsBySubCategory']);
Route::get('/productscat/{categoryId}', [ProductController::class, 'getProductsByCategory']);
// Route::get('/featured-products', [ProductController::class, 'getFeaturedProducts']);

Route::get('/slider', [SLiderController::class, 'getAllSliders']);

Route::get('/best-offers', [ProductController::class, 'getBestOffers']);
Route::get('/less-in-stock', [ProductController::class, 'getLessInStockProducts']);
Route::get('/new-products', [ProductController::class, 'getNewProducts']);
Route::get('/search-products', [ProductController::class, 'searchProducts']);

Route::post('/cart/add', [CartController::class, 'addToCart']);
Route::delete('/cart/remove/{cart_id}', [CartController::class, 'removeFromCart']);
Route::get('/cart/{user_id}', [CartController::class, 'getCart']);
Route::post('/cart/update-all', [CartController::class, 'updateAllQuantities']);

Route::get('/feature-brands', [FeatureBrandController::class, 'getAllFeatureBrands']);

Route::get('/productbyBrandId/{brand_id}', [ProductController::class, 'getProductsByBrandId']);

Route::post('/edit-profile/{id}', [GoogleAuthController::class, 'editProfile']);

Route::post('wishlist/toggle', [WishlistController::class, 'toggleWishlist']);
Route::get('wishlist/{user_id}', [WishlistController::class, 'getWishlist']);

Route::post('/place-order', [OrdersController::class, 'placeOrder']);
Route::get('/orders/{user_id}', [OrdersController::class, 'getOrdersByUser']);

Route::get('/settings',[SLiderController::class,'getSettings']);

Route::post('/update-order-status', [OrdersController::class, 'updateOrderStatus']);
