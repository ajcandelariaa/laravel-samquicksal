<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSetFoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'restAcc_id',
        'orderSet_id',
        'foodItem_id',
    ];
}
