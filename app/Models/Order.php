<?php

namespace App\Models;

use Google\Service\AdMob\App;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'total_amount',
        'discount_amount',
        'final_amount',
        'delivery_charge',
        'payment_method',
        'payment_status',
        'transaction_id',
        'paid_amount',
        'order_status',
        'cancel_reason',
        'address',
        'phone',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    public function user()
    {
        return $this->belongsTo(AppUser::class, 'user_id','id');
    }
}
