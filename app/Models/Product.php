<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_name',
        'product_description',
        'product_mrp',
        'product_price',
        'product_discount',
        'product_stock',
        'product_unit',
        'product_image',
        'is_active',
        'sub_category_id',
        'feature_brand_id',
        'category_id',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'sub_category_id');
    }
    public function featurebrand()
    {
        return $this->belongsTo(FeatureBrand::class, 'feature_brand_id', 'feature_brand_id');
    }
}
