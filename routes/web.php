<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\bOrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeatureBrandController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SLiderController;
use App\Http\Controllers\SubCategoryController;
use Illuminate\Support\Facades\Route;



// Route::get('/',function() {
//     return view('pages.sign-in');
// });

// Route::get('/', function(){
//     return view('pages.sign-in');
// })->name('sign-in');


Route::get('/', [AuthController::class, 'showLoginForm'])->name('sign-in');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/categories-index', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories-create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories-store', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories-edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
Route::post('/categories-update/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::get('/categories-delete/{id}', [CategoryController::class, 'delete'])->name('categories.delete');

Route::get('/subcategories-index', [SubCategoryController::class, 'index'])->name('subcategories.index');
Route::get('/subcategories-create', [SubCategoryController::class, 'create'])->name('subcategories.create');
Route::post('/subcategories-store', [SubCategoryController::class, 'store'])->name('subcategories.store');
Route::get('/subcategories-edit/{id}', [SubCategoryController::class, 'edit'])->name('subcategories.edit');
Route::post('/subcategories-update/{id}', [SubCategoryController::class, 'update'])->name('subcategories.update');
Route::get('/subcategories-delete/{id}', [SubCategoryController::class, 'delete'])->name('subcategories.delete');

Route::get('/products-index', [ProductController::class, 'index'])->name('products.index');
Route::get('/products-create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products-store', [ProductController::class, 'store'])->name('products.store');
Route::get('/products-edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
Route::post('/products-update/{id}', [ProductController::class, 'update'])->name('products.update');
Route::get('/products-delete/{id}', [ProductController::class, 'delete'])->name('products.delete');



Route::get('/settings-index', [SLiderController::class, 'index'])->name('settings.index');
Route::get('/sliders-create', [SLiderController::class, 'create'])->name('sliders.create');
Route::post('/sliders-store', [SliderController::class, 'store'])->name('sliders.store');
Route::get('/sliders-edit/{id}', [SliderController::class, 'edit'])->name('sliders.edit');
Route::post('/sliders-update/{id}', [SliderController::class, 'update'])->name('sliders.update');
Route::get('/sliders-delete/{id}', [SliderController::class, 'delete'])->name('sliders.delete');
Route::post('/settings-update', [SLiderController::class, 'updateSettings'])->name('settings.update');

Route::resource('/feature_brands', FeatureBrandController::class);

Route::get('/order-list',[bOrderController::class,'index'])->name('order.list');
Route::get('/order-details/{id}', [bOrderController::class, 'show'])->name('order.details');
Route::post('/admin/order/update-status', [bOrderController::class, 'updateStatus'])->name('order.update.status');

Route::get('/notification', [NotificationController::class, 'index'])->name('notifications.index');
Route::put('/notifications/update/{id}', [NotificationController::class, 'update'])->name('notifications.update');

// Route::get('/welcome-notification',[NotificationController::class,'welcomeNotification'])->name('welcome.notification');
