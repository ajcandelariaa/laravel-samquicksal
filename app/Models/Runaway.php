<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Runaway extends Model
{
    use HasFactory;

    protected $fillable = [
        'restAcc_id',
        'noOfRunaways',
        'noOfDays',
        'blockDays',
    ];
}
