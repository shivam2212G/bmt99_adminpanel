<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiSettings extends Model
{
    protected $table = 'api_settings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'new_products',
        'best_offers',
        'less_in_stock',
        'shop_address',
        'shop_phone',
        'shop_email',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'privacy_policy',
        'discamer',
    ];
}
