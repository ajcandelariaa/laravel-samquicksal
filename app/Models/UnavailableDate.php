<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnavailableDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'restAcc_id',
        'unavailableDatesDate',
        'startTime',
        'unavailableDatesDesc',
    ];
}
