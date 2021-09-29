<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodSetItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'foodSet_id',
        'foodItem_id',
    ];
}
