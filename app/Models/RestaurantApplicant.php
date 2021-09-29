<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantApplicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'fname',
        'mname',
        'lname',
        'address',
        'city',
        'postalCode',
        'state',
        'country',
        'role',
        'birthDate',
        'gender',
        'contactNumber',
        'landlineNumber',
        'emailAddress',

        'rName',
        'rBranch',
        'rAddress',
        'rCity',
        'rPostalCode',
        'rState',
        'rCountry',

        'bir',
        'dti',
        'mayorsPermit',
        'staffValidId'
    ];


}
