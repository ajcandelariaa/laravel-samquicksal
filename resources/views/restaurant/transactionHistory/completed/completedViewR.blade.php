@extends('restaurant.app4')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 pb-2">
        <a href="/restaurant/transaction-history/completed/reserve" class="text-submitButton uppercase font-bold">
            <i class="fas fa-chevron-left mr-2"></i>Back
        </a>
    </div>
    <div class="w-11/12 mx-auto mt-5 font-Montserrat bg-white mb-10">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white ml-8 text-xl py-3">Completed Customer Reserve Details</div>
        </div>
        <div class="w-11/12 mx-auto py-10">
            <div class="grid grid-cols-3 gap-5 items-start">

                <div class="col-span-2 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="bg-adminViewAccountHeaderColor2">
                        <div class="uppercase font-bold text-center text-xl py-4">Customer DETAILS</div>
                    </div>
                    <div class="bg-manageFoodItemHeaderBgColor py-5">
                        <div class="w-10/12 mx-auto grid grid-cols-customerDetailsGrid items-center">
                            <div>
                                @if ($customerReserve->customer_id == 0)
                                    <img src="{{ asset('images/user-default.png') }}" alt="customerImage" class="w-40 h-40 rounded-3xl">
                                @else
                                    @if ($customerInfo->profileImage == null)
                                        <img src="{{ asset('images/user-default.png') }}" alt="customerImage" class="w-40 h-40 rounded-3xl">
                                    @else
                                        <img src="{{ asset('uploads/customerAccounts/logo/'.$customerInfo->id.'/'.$customerInfo->profileImage) }}" alt="customerImage" class="w-40 h-40 rounded-3xl">
                                    @endif
                                @endif
                            </div>
                            <div>
                                <p class="mt-2">Name: 
                                    <span class="font-bold">
                                        @if ($customerReserve->customer_id == 0)
                                            {{ $customerReserve->name }}
                                        @else
                                            {{ $customerInfo->name }}
                                        @endif
                                    </span>
                                </p>
                                @if ($customerReserve->customer_id != 0)
                                    <p class="mt-2">Contact Number: <span class="font-bold">{{ $customerInfo->contactNumber }}</span></p>
                                    <p class="mt-2">No. of Book: <span class="font-bold">{{ $countQueues }}</span></p>
                                    <p class="mt-2">No. of No Show: <span class="font-bold">{{ $countNoShow }}</span></p>
                                @else
                                    <p class="mt-2">Contact Number: <span class="font-bold">N/A</span></p>
                                    <p class="mt-2">No. of Book: <span class="font-bold">N/A</span></p>
                                    <p class="mt-2">No. of No Show: <span class="font-bold">N/A</span></p>
                                @endif
                                <p class="mt-2">Booking Date: <span class="font-bold">{{ $bookDate }}</span></p>
                            </div>
                            <div>
                                @if ($customerReserve->customer_id != 0)
                                    <p class="mt-2">Email: <span class="font-bold">{{ $customerInfo->emailAddress }}</span></p>
                                    <p class="mt-2">No. of Cancel: <span class="font-bold">{{ $countCancelled }}</span></p>
                                    <p class="mt-2">No. of Runaway: <span class="font-bold">{{ $countRunaway }}</span></p>
                                @else
                                    <p class="mt-2">Email: <span class="font-bold">N/A</span></p>
                                    <p class="mt-2">No. of Cancel: <span class="font-bold">N/A</span></p>
                                    <p class="mt-2">No. of Runaway: <span class="font-bold">N/A</span></p>  
                                @endif
                                <p class="mt-2">User since: <span class="font-bold">{{ $userSinceDate }}</span></p>
                                <p class="mt-2">Booking Time: <span class="font-bold">{{ $bookTime }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1 row-span-4 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="col-span-2 row-span-5 bg-manageFoodItemHeaderBgColor">
                        <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">ORDER HISTORY</div>
                        <div class="py-5 w-11/12 mx-auto">
                            <div class="grid grid-cols-3 mt-2">
                                <p class="col-span-1">{{ $order }}</p>
                                <p class="col-span-1 text-center">{{ $customerReserve->numberOfPersons }}x</p>
                                <p class="justify-self-end col-span-1 font-bold">{{ (number_format($customerReserve->orderSetPrice * $customerReserve->numberOfPersons, 2, '.')) }} <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                            </div>
    
    
                            <hr class="my-3">
    
                            @if ($customerOrders->isEmpty())
                                <div class="mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 rounded-lg">
                                    <div class="text-center text-multiStepBoxColor uppercase">There are no orders as of now</div>
                                </div>
                            @else
                                @foreach ($customerOrders as $customerOrder)
                                    <div class="grid grid-cols-3 mt-2">
                                        <p class="col-span-1">{{ $customerOrder->foodItemName }}</p>
                                        <p class="col-span-1 text-center">{{ $customerOrder->quantity }}x</p>
                                        <p class="justify-self-end col-span-1 font-bold">{{ (number_format($customerOrder->price, 2, '.')) }} <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                                    </div>
                                @endforeach
                            @endif
                            
                            <hr class="my-3">
    
                            <div class="grid grid-cols-2 mt-2">
                                <p class="col-span-1 text-manageRestaurantSidebarColor">Subtotal</p>
                                <p class="justify-self-end col-span-1 font-bold text-submitButton text-lg">{{ (number_format($subTotal, 2, '.')) }} <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                            </div>
                            <div class="grid grid-cols-2 mt-2">
                                <p class="col-span-1">Service Fee (0%)</p>
                                <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                            </div>
                            <div class="grid grid-cols-2 mt-2">
                                <p class="col-span-1">Incl. Tax</p>
                                <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                            </div>
    
    
                            <hr class="my-3">
    
    
                            <div class="mt-2">
                                <p class="col-span-1 text-manageRestaurantSidebarColor">Discount</p>
                            </div>
                            <div class="grid grid-cols-3 mt-2">
                                <p class="col-span-1">Senior Citizen / PWD (20%)</p>
                                <p class="col-span-1 text-center">{{ $customerReserve->numberOfPwd }}x</p>
                                <p class="justify-self-end col-span-1 font-bold">{{ (number_format($seniorDiscount, 2, '.')) }} <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                            </div>
                            <div class="grid grid-cols-3 mt-2">
                                <p class="col-span-1">Children ({{ $customerReserve->childrenDiscount }}%)</p>
                                <p class="col-span-1 text-center">{{ $customerReserve->numberOfChildren }}x</p>
                                <p class="justify-self-end col-span-1 font-bold">{{ (number_format($childrenDiscount, 2, '.')) }} <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                            </div>
                            <div class="grid grid-cols-2 mt-2">
                                <p class="col-span-1">Reward Discount ({{ $finalReward }})</p>
                                <p class="justify-self-end col-span-1 font-bold">{{ (number_format($rewardDiscount, 2, '.')) }} <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                            </div>
                            <div class="grid grid-cols-2 mt-2">
                                <p class="col-span-1">Additional Discount</p>
                                <p class="justify-self-end col-span-1 font-bold">{{ (number_format($customerReserve->additionalDiscount, 2, '.')) }} <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                            </div>
                            <div class="grid grid-cols-2 mt-2">
                                <p class="col-span-1">Promo Discount</p>
                                <p class="justify-self-end col-span-1 font-bold">{{ (number_format($customerReserve->promoDiscount, 2, '.')) }} <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                            </div>
    
                            <hr class="my-3">
                            
                            <div class="mt-2">
                                <p class="col-span-1 text-manageRestaurantSidebarColor">Charges</p>
                            </div>
                            <div class="grid grid-cols-2 mt-2">
                                <p class="col-span-1">Offense Charges</p>
                                <p class="justify-self-end col-span-1 font-bold">{{ (number_format($customerReserve->offenseCharges, 2, '.')) }} <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                            </div>
    
    
                            <hr class="my-3">
    
                            
                            <div class="grid grid-cols-2 mt-2">
                                <p class="col-span-1 text-manageRestaurantSidebarColor">Total Price</p>
                                <p class="justify-self-end col-span-1 font-bold text-loginLandingPage text-lg">{{ (number_format($totalPrice, 2, '.')) }} <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-2 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="bg-adminViewAccountHeaderColor2 ">
                        <div class="uppercase font-bold text-center text-xl py-4">Order Details</div>
                    </div>
                    <div class="bg-manageFoodItemHeaderBgColor py-5">
                        <div class="w-11/12 mx-auto">
                            <div class="grid grid-cols-2">
                                <div>
                                    <p class="mt-2">Reserved Date: <span class="font-bold">{{ $reserveDate }}</span></p>
                                    <p class="mt-2">Completed Date: <span class="font-bold">{{ $cancelledDate }}</span></p>
                                    <p class="mt-2">Order Set: <span class="font-bold">{{ $customerReserve->orderSetName }}</span></p>
                                    <p class="mt-2">No. of Persons: <span class="font-bold">{{ $customerReserve->numberOfPersons }}</span></p>
                                    <p class="mt-2">Senior Citizen/PWD: <span class="font-bold">{{ $customerReserve->numberOfPwd }}</span></p>
                                    <p class="mt-2">Reward: <span class="font-bold">{{ $finalReward }}</span></p>
                                </div>
                                <div>
                                    <p class="mt-2">Reserved Time: <span class="font-bold">{{ $customerReserve->reserveTime }}</span></p>
                                    <p class="mt-2">Completed Time: <span class="font-bold">{{ $cancelledTime }}</span></p>
                                    <p class="mt-2">Hours of Stay: <span class="font-bold">{{ $customerReserve->hoursOfStay }} hours</span></p>
                                    <p class="mt-2">No. of Tables: <span class="font-bold">{{ $customerReserve->numberOfTables }}</span></p>
                                    <p class="mt-2">Children (7 below): <span class="font-bold">{{ $customerReserve->numberOfChildren }}</span></p>
                                    @if ($customerReserve->rewardClaimed != null)
                                        <p class="mt-2">Reward Claimed: <span class="font-bold">{{ $customerReserve->rewardClaimed }}</span></p>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-5 border-multiStepBoxBorder w-10/12 p-4">
                                <p>Notes: <span class="font-bold">{{ $customerReserve->notes }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-2">
                    <div class="uppercase font-bold text-lg py-2 col-span-1">CUSTOMER WITH QR ACCESS</div>
                    <div class="grid grid-cols-2 gap-x-3 items-start">
                        @if ($finalCustomerAccess == null)
                            <div class="col-span-2 mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 rounded-lg">
                                <div class="text-center text-multiStepBoxColor uppercase">There are no sub customers that has been made</div>
                            </div>
                        @else
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($finalCustomerAccess as $customer)
                                <div class="col-span-1 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                                    <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">Customer DETAILS</div>
                                    <div class="grid grid-cols-orderCustDetailsGrid items-center py-7 gap-x-5 w-10/12 mx-auto">
                                        <div>
                                            @if ($customer['custImage'] == null)
                                                <img src="{{ asset('images/user-default.png') }}" alt="customerImage" class="w-24 h-24 rounded-3xl">
                                            @else
                                                <img src="{{ asset('uploads/customerAccounts/logo/'.$customer['custId'].'/'.$customer['custImage']) }}" alt="customerImage" class="w-24 h-24 rounded-3xl start">
                                            @endif
                                        </div>
                                        <div>
                                            <p>Name: {{ $customer['custName'] }}</p>
                                            <p>Table Number: {{ $customer['tableNumber'] }}</p>
                                            <p>User Since: {{ $customer['userSince'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @php
                                $count++
                            @endphp
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </div>
        <div class="bg-manageRestaurantSidebarColorActive py-5"></div>
    </div>
</div>
@endsection