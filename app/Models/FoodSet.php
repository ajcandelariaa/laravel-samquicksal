<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'restAcc_id',
        'foodSetName',
        'foodSetDescription',
        'foodSetPrice',
        'foodSetImage',
    ];
}
