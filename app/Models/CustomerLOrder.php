<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'custOrdering_id',
        'cust_id',
        'tableNumber',
        'foodItem_id',
        'foodItemName',
        'quantity',
        'price',
        'orderDone',
        'orderSubmitDT',
    ];
}
