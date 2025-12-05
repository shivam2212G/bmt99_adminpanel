<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    protected $primaryKey = 'sub_category_id';
    protected $fillable = [
        'sub_category_name',
        'sub_category_description',
        'sub_category_image',
        'is_active',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
