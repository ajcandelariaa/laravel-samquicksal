<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'resApp_id',
        'verified',
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

        'username',
        'password',

        'rName',
        'rBranch',
        'rAddress',
        'rCity',
        'rPostalCode',
        'rState',
        'rCountry',

        'rLatitudeLoc',
        'rLongitudeLoc',
        'rRadius',

        'rNumberOfTables',
        'r2seater',
        'r3seater',
        'r4seater',
        'r5seater',
        'r6seater',
        'r7seater',
        'r8seater',
        'r9seater',
        'r10seater',
        'rGcashQrCodeImage',
        'rLogo',

        'rTimeLimit',

        'bir',
        'dti',
        'mayorsPermit',
        'staffValidId'
    ];
}