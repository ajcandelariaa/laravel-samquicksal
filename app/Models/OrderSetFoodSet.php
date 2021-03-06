<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSetFoodSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'orderSet_id',
        'foodSet_id',
    ];
}
