<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTasksDone extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'customer_id',
        'customerStampCard_id',
        'taskName',
        'taskAccomplishDate',
        'booking_id',
        'booking_type',
    ];
}
