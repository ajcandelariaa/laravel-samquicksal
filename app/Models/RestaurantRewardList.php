<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantRewardList extends Model
{
    use HasFactory;

    protected $fillable = [
        'restAcc_id',
        'rewardCode',
        'rewardTitle',
        'rewardDescription',
        'rewardInput',
    ];
}
