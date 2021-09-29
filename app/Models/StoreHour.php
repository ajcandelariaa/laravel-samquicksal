<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'restAcc_id',
        'openingTime',
        'closingTime',
        'days',
        'type',
    ];
}
