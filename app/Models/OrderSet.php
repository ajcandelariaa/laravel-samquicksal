<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'restAcc_id',
        'status',
        'available',
        'orderSetName',
        'orderSetTagline',
        'orderSetDescription',
        'orderSetPrice',
        'orderSetImage',
    ];
}
