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
        'orderSetName',
        'orderSetPrice',
        'status',
        'checkoutStatus',
        'gcashCheckoutReceipt',
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
        'runawayDateTime',
        'completeDateTime',
        'declinedReason',
        'cancelReason',
    ];
}
