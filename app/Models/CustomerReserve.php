<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReserve extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'restAcc_id',
        'orderSet_id',
        'status',
        'reserveDate',
        'reserveTime',
        'numberOfPersons',
        'numberOfTables',
        'hoursOfStay',
        'numberOfChildren',
        'numberOfPwd',
        'totalPwdChild',
        'notes',
        'rewardStatus',
        'rewardType',
        'rewardInput',
        'rewardClaimed',
        'totalPrice',
        'childrenDiscount',
        'additionalDiscount',
        'promoDiscount',
        'offenseCharges',
        'approvedDateTime',
        'cancelDateTime',
        'declinedDateTime',
        'validationDateTime',
        'tableSettingDateTime',
        'eatingDateTime',
        'checkoutDateTime',
        'completeDateTime',
        'declinedReason',
        'cancelReason',
    ];
}
