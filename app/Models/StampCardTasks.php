<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampCardTasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'stampCards_id',
        'restaurantTaskLists_id',
    ];
}
