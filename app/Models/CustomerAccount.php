<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'emailAddress',
        'contactNumber',
        'password',
        'profileImage',
    ];
}
