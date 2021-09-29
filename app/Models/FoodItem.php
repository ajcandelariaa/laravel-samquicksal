<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'restAcc_id',
        'foodItemName',
        'foodItemDescription',
        'foodItemPrice',
        'foodItemImage',
    ];
}
