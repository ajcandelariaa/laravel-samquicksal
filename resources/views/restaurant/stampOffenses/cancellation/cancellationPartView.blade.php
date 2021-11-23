@extends('restaurant.app5')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 pb-2">
        <a href="/restaurant/stamp-offenses/offenses/cancellation" class="text-submitButton uppercase font-bold">
            <i class="fas fa-chevron-left mr-2"></i>Back
        </a>
    </div>
    <div class="w-11/12 mx-auto mt-5 font-Montserrat bg-white mb-10">
        <div class="bg-manageRestaurantSidebarColorActive grid grid-cols-2 px-5 py-1 items-center">
            <div class="uppercase font-bold text-white ml-8 text-xl py-3">BLOCKED ACCOUNT DETAILS</div>
            <div class="justify-self-end text-white py-3 ml-3">
                <a href="/restaurant/stamp-offenses/offenses/cancellation/delete/{{ $custMainOffenses->id }}" id="btn-delete-block" class="px-8 py-2 hover:bg-darkerSubmitButton bg-headerActiveTextColor">Delete Block</a>
            </div>
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
                        <p class="mt-2">No. of Block: <span class="font-bold">{{ $custBlockCount }}</span></p>
                        <p class="mt-2">Total Offense: <span class="font-bold">{{ $custMainOffenses->totalOffense }}</span></p>
                        <p class="mt-2">Offense Days Block: <span class="font-bold">
                            @if ($custMainOffenses->offenseDaysBlock == "Permanent")
                                {{ $custMainOffenses->offenseDaysBlock }} Block
                            @else
                                {{ $custMainOffenses->offenseDaysBlock }} Day/s of Block
                            @endif
                        </span></p>
                    </div>
                    <div>
                        <p class="mt-2">Email: <span class="font-bold">{{ $customerInfo->emailAddress }}</span></p>
                        <p class="mt-2">User since: <span class="font-bold">{{ $userSinceDate }}</span></p>
                        <p class="mt-2">No. of Cancellation: <span class="font-bold">{{ $countCancelled }}</span></p>
                        <p class="mt-2">Offense Maximum Limit: <span class="font-bold">{{ $custMainOffenses->offenseCapacity }}</span></p>
                        <p class="mt-2">Offense Validity: <span class="font-bold">{{ $offenseValidity }}</span></p>
                    </div>
                </div>
            </div>
            <div class="bg-adminViewAccountHeaderColor2 mt-10">
                <div class="uppercase font-bold text-center text-xl py-4">OFFENSE DETAILS</div>
            </div>
            <div class="bg-manageFoodItemHeaderBgColor py-5 shadow-adminDownloadButton">
                <div class="w-11/12 mx-auto">
                    
                    <div class="w-11/12 mx-auto font-Montserrat bg-adminViewAccountHeaderColor2 pb-2">
                        
                        <div class="grid grid-rows-1 gap-y-2">
                            <div class="grid grid-cols-9 pt-4 pb-2 text-center">
                                <div class="col-span-1">No.</div>
                                <div class="col-span-2">Date</div>
                                <div class="col-span-2">Time</div>
                                <div class="col-span-2">Book Type</div>
                                <div class="col-span-2">View Transaction</div>
                            </div>
                
                            @if ($storeOffenseEach != null)
                                @php
                                    $count = 1
                                @endphp
                                @for ($i=0; $i<sizeOf($storeOffenseEach); $i++)
                                    <div class="grid grid-cols-9 py-2 text-center bg-white">
                                        <div class="col-span-1">{{ $count }}</div>
                                        <div class="col-span-2">{{ $storeOffenseEach[$i]['date'] }}</div>
                                        <div class="col-span-2">{{ $storeOffenseEach[$i]['time'] }}</div>
                                        <div class="col-span-2">{{ $storeOffenseEach[$i]['bookType'] }}</div>
                                        <div class="col-span-2">
                                            <a href="{{ $storeOffenseEach[$i]['transactionLink'] }}" target="_blank">
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
        <div class="bg-manageRestaurantSidebarColorActive py-5"></div>
    </div>
</div>
@endsection