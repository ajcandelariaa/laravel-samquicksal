<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'custOrdering_id',
        'tableNumber',
        'request',
        'requestDone',
    ];
}
