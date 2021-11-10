<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustOffenseMain extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'restAcc_id',
        'offenseType',
        'totalOffense',
        'offenseCapacity',
        'offenseDaysBlock',
        'offenseValidity',
    ];
}
