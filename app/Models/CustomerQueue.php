<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerQueue extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'restAcc_id',
        'orderSet_id',
        'status',
        'checkoutStatus',
        'gcashCheckoutReceipt',
        'name',
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
        'queueDate',
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
