@extends('restaurant.app3')

@section('content')
<div class="container mx-auto font-Montserrat mb-10 booking">
    @if (session()->has('validation'))
        <script>
            Swal.fire(
                'Customer is now on Validation',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('inputCorrectFormat'))
        <script>
            Swal.fire(
                'Please input the correct format',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('inputCorrectNumberOfTables'))
        <script>
            Swal.fire(
                'Please input the corret number of tables',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('inputCorrectTableNumber'))
        <script>
            Swal.fire(
                'Please input the corret Table Number',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('mustBeDiffrentTables'))
        <script>
            Swal.fire(
                'Please input different number of table',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('tableExist'))
        <script>
            Swal.fire(
                'Table Number already exist',
                'Please input different number of tables',
                'error'
            );
        </script>
    @endif
    
    <div class="w-11/12 mx-auto mt-10 pb-2 grid grid-cols-2">
        <a href="/restaurant/live-transaction/approved-customer/queue" class="text-submitButton uppercase font-bold"><i class="fas fa-chevron-left mr-2"></i>Back</a>
        @if (!$isPriority)
            <p class="text-submitButton uppercase font-bold text-right">Please validate the customer queue in order</p>
        @elseif ($remainingTables < $customerQueue->numberOfTables)
            <p class="text-submitButton uppercase font-bold text-right">Please wait for another table to checkout</p>
        @endif

        @if ($customerQueue->status == "validation")
            <p class="text-submitButton uppercase font-bold text-right">Validation Time Left: {{ $validationTimeLeft }}</p>
        @endif
    </div>
    <div class="w-11/12 mx-auto mt-5 font-Montserrat bg-white">
        <div class="bg-manageRestaurantSidebarColorActive grid grid-cols-2 px-5 py-3 items-center">
            <div class="uppercase font-bold text-white text-xl py-3">BOOKING DETAILS</div>
            <div class="justify-self-end text-white py-3 ml-3">

                @if ($customerQueue->status == "approved")
                    @if (!$isPriority)
                        <button type="button" class="px-8 py-2 bg-manageRestaurantSidebarColor cursor-not-allowed" disabled>Validate</button>
                    @elseif ($remainingTables < $customerQueue->numberOfTables)
                        <button type="button" class="px-8 py-2 bg-manageRestaurantSidebarColor cursor-not-allowed" disabled>Validate</button>
                    @else
                        <a href="/restaurant/live-transaction/approved-customer/queue/validate/{{ $customerQueue->id }}" class="btn-validate px-8 py-2 bg-postedStatus ml-2">Validate</a>
                    @endif
                @else
                    <a href="/restaurant/live-transaction/approved-customer/queue/no-show/{{ $customerQueue->id }}" class="btn-no-show px-8 py-2 bg-adminDeleteFormColor ml-2">No show</a>
                    <button id="btn-admit" class="px-8 py-2 bg-postedStatus ml-2">Admit</button>
                @endif

            </div>
        </div>
        <div class="w-11/12 mx-auto py-10">
            <div class="grid grid-cols-3 gap-x-3">
                <div class="col-span-2">
                    <div class="bg-adminViewAccountHeaderColor2">
                        <div class="uppercase font-bold text-center text-xl py-4">Customer DETAILS</div>
                    </div>
                    <div class="bg-manageFoodItemHeaderBgColor py-5 shadow-adminDownloadButton">
                        <div class="w-10/12 mx-auto grid grid-cols-customerDetailsGrid items-center">
                            <div>
                                @if ($customerQueue->customer_id == 0)
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
                                        @if ($customerQueue->customer_id == 0)
                                            {{ $customerQueue->name }}
                                        @else
                                            {{ $customerInfo->name }}
                                        @endif
                                    </span>
                                </p>
                                @if ($customerQueue->customer_id != 0)
                                    <p class="mt-2">Contact Number: <span class="font-bold">{{ $customerInfo->contactNumber }}</span></p>
                                @else
                                    <p class="mt-2">Contact Number: <span class="font-bold">N/A</span></p>
                                @endif
                                <p class="mt-2">No. of Queues: <span class="font-bold">{{ $countQueues }}</span></p>
                                <p class="mt-2">No. of No Show: <span class="font-bold">{{ $countNoShow }}</span></p>
                                <p class="mt-2">Booking Date: <span class="font-bold">{{ $bookDate }}</span></p>
                            </div>
                            <div>
                                @if ($customerQueue->customer_id != 0)
                                    <p class="mt-2">Email: <span class="font-bold">{{ $customerInfo->emailAddress }}</span></p>
                                @else
                                    <p class="mt-2">Email: <span class="font-bold">N/A</span></p>
                                @endif
                                <p class="mt-2">User since: <span class="font-bold">{{ $userSinceDate }}</span></p>
                                <p class="mt-2">No. of Cancel: <span class="font-bold">{{ $countCancelled }}</span></p>
                                <p class="mt-2">No. of Runaway: <span class="font-bold">{{ $countRunaway }}</span></p>
                                <p class="mt-2">Booking Time: <span class="font-bold">{{ $bookTime }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1 self-stretch">
                    <div class="bg-adminViewAccountHeaderColor2">
                        <div class="uppercase font-bold text-center text-xl py-4">QUEUE NUMBER</div>
                    </div>
                    <div class="bg-manageFoodItemHeaderBgColor py-5 shadow-adminDownloadButton text-center">
                        <p class="text-9xl text-headerActiveTextColor font-bold">
                            {{ $finalQueueNumber }}
                        </p>
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
                                <p class="mt-2">Order Set: <span class="font-bold">{{ $customerQueue->orderSetName }}</span></p>
                                <p class="mt-2">No. of Persons: <span class="font-bold">{{ $customerQueue->numberOfPersons }}</span></p>
                                <p class="mt-2">Senior Citizen/PWD: <span class="font-bold">{{ $customerQueue->numberOfPwd }}</span></p>
                                <p class="mt-2">Reward: <span class="font-bold">{{ $finalReward }}</span></p>
                            </div>
                            <div>
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
                            <p class="col-span-1">{{ $customerQueue->orderSetName }}</p>
                            <p class="col-span-1 text-center">{{ $customerQueue->numberOfPersons }}x</p>
                            <p class="justify-self-end col-span-1">{{ (number_format(($customerQueue->numberOfPersons * $customerQueue->orderSetPrice), 2)) }} <span class="text-xs">Php</span></p>
                        </div>

                        <div class="bg-sundayToSaturdayBoxColor h-height1Px my-2"></div>

                        <div class="grid grid-cols-2 mt-2">
                            <p class="col-span-1">Service Fee</p>
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
                            <p class="col-span-1">Children (7 below)</p>
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
                            <p class="col-span-1">Total Price</p>
                            <p class="justify-self-end col-span-1"><span class="text-loginLandingPage text-lg font-bold">{{ $customerQueue->totalPrice }}</span> <span class="text-xs">Php</span></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="bg-manageRestaurantSidebarColorActive h-8"></div>

        {{-- POP UP FORMS --}}
        <div class="admit-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl font-Montserrat">Admit Customer</h1>
            <form action="/restaurant/live-transaction/approved-customer/queue/admit/{{ $customerQueue->id }}" method="POST" id="admitForm">
                @csrf
                <div class="w-10/12 mx-auto mt-10 text-center">
                    <p>Input Table Number/s:</p>
                    <p class="mt-2 text-xs italic text-gray-500">Please input the table number seperated by comma</p>
                    <input type="text" name="tableNumbers" placeholder="(e.g 1,2,3)" id="inputTableNum" class="mt-5 border rounded-md focus:border-black w-full py-3 px-2 text-sm focus:outline-non text-gray-700" required>
                </div>
                <div class="text-center mt-10">
                    <button id="btnAdmit" class="bg-submitButton text-white hover:bg-darkerSubmitButton hover:text-gray-300 rounded-md w-32 h-10 text-sm uppercase font-bold transition duration-200 ease-in-out" type="submit">Submit</button>
                </div>
            </form>
        </div>
        <div class="overlay"></div>
    </div>
</div>
@endsection