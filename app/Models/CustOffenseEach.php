<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustOffenseEach extends Model
{
    use HasFactory;

    protected $fillable = [
        'custOffMain_id',
        'customer_id',
        'restAcc_id',
        'book_id',
        'book_type',
        'offenseType',
    ];
}
