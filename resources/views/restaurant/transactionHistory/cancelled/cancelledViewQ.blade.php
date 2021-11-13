@extends('restaurant.app4')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 pb-2">
        <a href="/restaurant/transaction-history/cancelled/queue" class="text-submitButton uppercase font-bold">
            <i class="fas fa-chevron-left mr-2"></i>Back
        </a>
    </div>
    <div class="w-11/12 mx-auto mt-5 font-Montserrat bg-white mb-10">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white ml-8 text-xl py-3">Cancelled Customer Queue Details</div>
        </div>
        <div class="w-11/12 mx-auto py-10">
            <div class="bg-adminViewAccountHeaderColor2">
                <div class="uppercase font-bold text-center text-xl py-4">Customer DETAILS</div>
            </div>
            <div class="bg-manageFoodItemHeaderBgColor py-5 shadow-adminDownloadButton">
                <div class="w-10/12 mx-auto grid grid-cols-customerDetailsGrid items-center">
                    <div>
                        @if ($customerInfo->profileImage == null)
                            <img src="{{ asset('images/user-default.png') }}" alt="customerImage" class="w-40 h-40 rounded-3xl">
                        @else
                            <img src="{{ asset('uploads/customerAccounts/logo/'.$customerInfo->id.'/'.$customerInfo->profileImage) }}" alt="customerImage" class="w-40 h-40 rounded-3xl">
                        @endif
                    </div>
                    <div>
                        <p class="mt-2">Name: <span class="font-bold">{{ $customerInfo->name }}</span></p>
                        <p class="mt-2">Contact Number: <span class="font-bold">{{ $customerInfo->contactNumber }}</span></p>
                        <p class="mt-2">No. of Book: <span class="font-bold">{{ $countQueues }}</span></p>
                        <p class="mt-2">No. of No Show: <span class="font-bold">{{ $countNoShow }}</span></p>
                        <p class="mt-2">Booking Date: <span class="font-bold">{{ $bookDate }}</span></p>
                    </div>
                    <div>
                        <p class="mt-2">Email: <span class="font-bold">{{ $customerInfo->emailAddress }}</span></p>
                        <p class="mt-2">User since: <span class="font-bold">{{ $userSinceDate }}</span></p>
                        <p class="mt-2">No. of Cancel: <span class="font-bold">{{ $countCancelled }}</span></p>
                        <p class="mt-2">No. of Runaway: <span class="font-bold">{{ $countRunaway }}</span></p>
                        <p class="mt-2">Booking Time: <span class="font-bold">{{ $bookTime }}</span></p>
                    </div>
                </div>
            </div>
            <div class="bg-adminViewAccountHeaderColor2 mt-10">
                <div class="uppercase font-bold text-center text-xl py-4">Order Details</div>
            </div>
            <div class="bg-manageFoodItemHeaderBgColor py-5 shadow-adminDownloadButton">
                <div class="w-11/12 mx-auto grid grid-cols-3">
                    <div class="col-span-2">
                        <div class="grid grid-cols-2">
                            <div>
                                <p class="mt-2">Cancelled Date: <span class="font-bold">{{ $cancelledDate }}</span></p>
                                <p class="mt-2">Order Set: <span class="font-bold">{{ $orderSet->orderSetName }}</span></p>
                                <p class="mt-2">No. of Persons: <span class="font-bold">{{ $customerQueue->numberOfPersons }}</span></p>
                                <p class="mt-2">Senior Citizen/PWD: <span class="font-bold">{{ $customerQueue->numberOfPwd }}</span></p>
                                <p class="mt-2">Reward: <span class="font-bold">{{ $finalReward }}</span></p>
                            </div>
                            <div>
                                <p class="mt-2">Cancelled Time: <span class="font-bold">{{ $cancelledTime }}</span></p>
                                <p class="mt-2">Hours of Stay: <span class="font-bold">{{ $customerQueue->hoursOfStay }} hours</span></p>
                                <p class="mt-2">No. of Tables: <span class="font-bold">{{ $customerQueue->numberOfTables }}</span></p>
                                <p class="mt-2">Children (7 below): <span class="font-bold">{{ $customerQueue->numberOfChildren }}</span></p>
                                @if ($customerQueue->rewardClaimed != null)
                                    <p class="mt-2">Reward Claimed: <span class="font-bold">{{ $customerQueue->rewardClaimed }}</span></p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-5 border-multiStepBoxBorder w-10/12 p-4">
                            <p>Notes: <span class="font-bold">{{ $customerQueue->notes }}</span></p>
                        </div>
                    </div>
                    <div class="col-span-1">
                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">{{ $orderSet->orderSetName }}</p>
                            <p class="col-span-1 text-center">{{ $customerQueue->numberOfPersons }}x</p>
                            <p class="justify-self-end col-span-1">{{ (number_format(($customerQueue->numberOfPersons * $orderSet->orderSetPrice), 2)) }} <span class="text-xs">Php</span></p>
                        </div>

                        <div class="bg-sundayToSaturdayBoxColor h-height1Px my-2"></div>

                        <div class="grid grid-cols-2 mt-2">
                            <p class="col-span-1">Service Fee (0%)</p>
                            <p class="justify-self-end col-span-1">0.00 <span class="text-xs">Php</span></p>
                        </div>

                        <div class="grid grid-cols-2 mt-2">
                            <p class="col-span-1">Incl. Tax</p>
                            <p class="justify-self-end col-span-1">0.00 <span class="text-xs">Php</span></p>
                        </div>

                        <div class="bg-sundayToSaturdayBoxColor h-height1Px my-2"></div>

                        <p class="text-manageRestaurantSidebarColor">Discount:</p>
                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">Senior Citizen (20%)</p>
                            <p class="col-span-1 text-center">{{ $customerQueue->numberOfPwd }}x</p>
                            <p class="justify-self-end col-span-1">{{ (number_format($seniorDiscount, 2)) }} <span class="text-xs">Php</span></p>
                        </div>
                        
                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">Children (0%)</p>
                            <p class="col-span-1 text-center">{{ $customerQueue->numberOfChildren }}x</p>
                            <p class="justify-self-end col-span-1">{{ (number_format($childrenDiscount, 2)) }} <span class="text-xs">Php</span></p>
                        </div>
                        
                        @if ($customerQueue->rewardClaimed == "Yes")
                            <div class="grid grid-cols-2 mt-2">
                                <p class="col-span-1">Reward ({{ $finalReward }})</p>
                                <p class="justify-self-end col-span-1">{{ (number_format($rewardDiscount, 2)) }} <span class="text-xs">Php</span></p>
                            </div>
                        @endif

                        <div class="bg-sundayToSaturdayBoxColor h-height1Px my-2"></div>

                        <div class="grid grid-cols-2 mt-2">
                            <p class="col-span-1">Estimated Total Price</p>
                            <p class="justify-self-end col-span-1"><span class="text-loginLandingPage text-lg font-bold">{{ $customerQueue->totalPrice }}</span> <span class="text-xs">Php</span></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="bg-manageRestaurantSidebarColorActive py-5"></div>
    </div>
</div>
@endsection