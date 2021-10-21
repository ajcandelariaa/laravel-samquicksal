<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestStampTasksHis extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'restStampCardHis_id',
        'taskName',
    ];
}
