<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustRestoRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'restAcc_id',
        'custOrdering_id',
        'rating',
        'comment',
        'anonymous',
    ];
}
