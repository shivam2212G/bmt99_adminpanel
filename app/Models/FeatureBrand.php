<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureBrand extends Model
{
    use HasFactory;
    protected $table = 'feature_brands';
    protected $primaryKey = 'feature_brand_id';
    protected $fillable = [
        'feature_brand_name',
        'feature_brand_image',
        'is_active',
    ];
    public function products()
    {
        return $this->hasMany(Product::class, 'feature_brand_id', 'feature_brand_id');
    }
}
