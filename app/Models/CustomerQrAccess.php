<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerQrAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'custOrdering_id',
        'mainCust_id',
        'subCust_id',
        'tableNumber',
        'status',
        'approvedDateTime',
    ];
}
