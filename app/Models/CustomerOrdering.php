<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrdering extends Model
{
    use HasFactory;

    protected $fillable = [
        'custBook_id',
        'restAcc_id',
        'custName',
        'custBookType',
        'tableNumbers',
        'availableQrAccess',
        'grantedAccess',
        'status',
    ];
}
