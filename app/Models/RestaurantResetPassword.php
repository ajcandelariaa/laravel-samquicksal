<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantResetPassword extends Model
{
    use HasFactory;

    protected $fillable = [
        'emailAddress',
        'token',
        'resetStatus',
    ];
}
