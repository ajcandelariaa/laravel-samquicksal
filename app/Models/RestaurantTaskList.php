<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTaskList extends Model
{
    use HasFactory;

    protected $fillable = [
        'restAcc_id',
        'taskCode',
        'taskTitle',
        'taskDescription',
        'taskInput',
    ];
}
