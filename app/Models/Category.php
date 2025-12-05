<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    protected $fillable = [
        'category_name',
        'category_description',
        'category_image',
        'is_active',
    ];
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id', 'category_id');
    }
}
