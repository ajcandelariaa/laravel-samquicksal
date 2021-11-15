@extends('restaurant.app5')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 pb-2">
        <a href="/restaurant/stamp-offenses/customer-stamp-history" class="text-submitButton uppercase font-bold">
            <i class="fas fa-chevron-left mr-2"></i>Back
        </a>
    </div>
    <div class="w-11/12 mx-auto mt-5 font-Montserrat bg-white pb-2">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white text-center text-xl py-3">Customer Stamp Card Details</div>
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
                        <p class="mt-2">Current Stamps: <span class="font-bold">{{ $custStampCards->currentStamp }}</span></p>
                        <p class="mt-2">Stamp Reward: <span class="font-bold">{{ $custStampCards->stampReward }}</span></p>
                        <p class="mt-2">Claimed: <span class="font-bold">{{ $custStampCards->claimed }}</span></p>
                    </div>
                    <div>
                        <p class="mt-2">Email: <span class="font-bold">{{ $customerInfo->emailAddress }}</span></p>
                        <p class="mt-2">User since: <span class="font-bold">{{ $userSinceDate }}</span></p>
                        <p class="mt-2">Stamp Capacity: <span class="font-bold">{{ $custStampCards->stampCapacity }}</span></p>
                        <p class="mt-2">Stamp Validity: <span class="font-bold">{{ $stampValidity }}</span></p>
                        @if ($custStampCards->claimed == "Yes")
                            <p class="mt-2">Claimed Date: <span class="font-bold">{{ $claimedDate }}</span></p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-x-5">
                <div class="col-span-9">
                    <div class="bg-adminViewAccountHeaderColor2 mt-10">
                        <div class="uppercase font-bold text-center text-xl py-4">Customer Tasks History</div>
                    </div>
                    <div class="bg-manageFoodItemHeaderBgColor py-5 shadow-adminDownloadButton">
                        <div class="w-11/12 mx-auto">

                            <div class="font-Montserrat bg-adminViewAccountHeaderColor2 pb-2">
                                <div class="grid grid-rows-1 gap-y-2">
                                    <div class="grid grid-cols-12 pt-4 pb-2 text-center">
                                        <div class="col-span-1">No.</div>
                                        <div class="col-span-4">Task Name</div>
                                        <div class="col-span-3">Accomplish Date</div>
                                        <div class="col-span-2">Book Type</div>
                                        <div class="col-span-2">Transaction</div>
                                    </div>
                        
                                    @if ($storeTasksDone != null)
                                        @php
                                            $count = 1
                                        @endphp
                                        @for ($i=0; $i<sizeOf($storeTasksDone); $i++)
                                            <div class="grid grid-cols-12 py-2 text-center bg-white">
                                                <div class="col-span-1">{{ $count }}</div>
                                                <div class="col-span-4">{{ $storeTasksDone[$i]['taskName'] }}</div>
                                                <div class="col-span-3">{{ $storeTasksDone[$i]['taskDate'] }}</div>
                                                <div class="col-span-2">{{ $storeTasksDone[$i]['bookType'] }}</div>
                                                <div class="col-span-2">
                                                    <a href="{{ $storeTasksDone[$i]['transactionLink'] }}" target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @php
                                                $count++
                                            @endphp
                                        @endfor
                                    @endif
                                </div>
                            </div>
        
                        </div>
                    </div>
                </div>
                
                <div class="col-span-3 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton mt-10">
                    <div class="bg-adminViewAccountHeaderColor2">
                        <div class="uppercase font-bold text-center text-xl py-4">Tasks</div>
                    </div>
                    <div class="pt-3 pb-5 ">
                        <div class="w-11/12 mx-auto">
                            @php
                                $count2 = 1
                            @endphp
                            @foreach ($customerTasks as $customerTask)
                                <p class="text-sm mt-2">{{ "$count2. $customerTask->taskName" }}</p>
                                @php
                                    $count2++
                                @endphp
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="bg-manageRestaurantSidebarColorActive py-5"></div>
    </div>
</div>
@endsection