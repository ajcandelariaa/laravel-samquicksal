<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'restAcc_id',
        'stampCapacity',
        'stampReward_id',
        'stampValidity',
    ];
}
