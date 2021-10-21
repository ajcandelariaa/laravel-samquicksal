<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerStampCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'restAcc_id',
        'status',
        'claimed',
        'currentStamp',
        'stampCapacity',
        'stampReward',
        'stampValidity',
    ];
}
