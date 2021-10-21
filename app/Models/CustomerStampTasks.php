<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerStampTasks extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'customerStampCard_id',
        'taskName',
    ];
}
