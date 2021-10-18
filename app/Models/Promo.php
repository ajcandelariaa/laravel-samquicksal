<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'restAcc_id',
        'promoPosted',
        'promoTitle',
        'promoDescription',
        'promoStartDate',
        'promoEndDate',
        'promoImage',
    ];
}
