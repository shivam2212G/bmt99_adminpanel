<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Appuser extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'appusers';

    protected $primaryKey = 'id';

    protected $fillable = [
        'google_id',
        'name',
        'email',
        'avatar',
        'address',
        'phone',
        'email_verified',
        'email_verified_at',
        'login_type',
        'app_token',
    ];
}
