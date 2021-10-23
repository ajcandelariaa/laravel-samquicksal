@extends('restaurant.app3')

@section('content')
<div class="container mx-auto font-Montserrat mb-10 booking">
    <div class="w-11/12 mx-auto mt-10 pb-2">
        <a href="/restaurant/live-transaction/customer-booking/queue" class="text-submitButton uppercase font-bold"><i class="fas fa-chevron-left mr-2"></i>Back</a>
    </div>
    <div class="w-11/12 mx-auto mt-5 font-Montserrat bg-white">
        <div class="bg-manageRestaurantSidebarColorActive grid grid-cols-2 px-5 py-3 items-center">
            <div class="uppercase font-bold text-white text-xl py-3">BOOKING DETAILS</div>
            <div class="justify-self-end text-white py-3 ml-3">
                <button id="btn-decline" class="px-8 py-2 bg-adminDeleteFormColor">Decline</button>
                <a href="/restaurant/live-transaction/customer-booking/reserve/approve/1" class="btn-approve px-8 py-2 bg-postedStatus ml-2">Approve</a>
            </div>
        </div>
        <div class="w-11/12 mx-auto py-10">
            <div class="bg-adminViewAccountHeaderColor2">
                <div class="uppercase font-bold text-center text-xl py-4">Customer DETAILS</div>
            </div>
            <div class="bg-manageFoodItemHeaderBgColor py-5 shadow-adminDownloadButton">
                <div class="w-10/12 mx-auto grid grid-cols-customerDetailsGrid items-center">
                    <div>
                        <img src="{{ asset('images/user-default.png') }}" alt="customerImage" class="w-40 h-40 rounded-3xl">
                    </div>
                    <div>
                        <p class="mt-2">Name: <span class="font-bold">Bianca Beatrix R. Quicho</span></p>
                        <p class="mt-2">Contact Number: <span class="font-bold">09123123123</span></p>
                        <p class="mt-2">No. of Queues: <span class="font-bold">2</span></p>
                        <p class="mt-2">No. of No Show: <span class="font-bold">3</span></p>
                        <p class="mt-2">Booking Date: <span class="font-bold">October 21, 2021</span></p>
                    </div>
                    <div>
                        <p class="mt-2">Email: <span class="font-bold">trix0428@gmail.com</span></p>
                        <p class="mt-2">User since: <span class="font-bold">September 20, 2021</span></p>
                        <p class="mt-2">No. of Cancel: <span class="font-bold">6</span></p>
                        <p class="mt-2">No. of Runaway: <span class="font-bold">0</span></p>
                        <p class="mt-2">Booking Time: <span class="font-bold">10:00 AM</span></p>
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
                                <p class="mt-2">Reservation Date: <span class="font-bold">October 23, 2021</span></p>
                                <p class="mt-2">Order Set: <span class="font-bold">Order Set 399</span></p>
                                <p class="mt-2">No. of Persons: <span class="font-bold">3</span></p>
                                <p class="mt-2">Senior Citizen/PWD: <span class="font-bold">2</span></p>
                            </div>
                            <div>
                                <p class="mt-2">Reservation Time: <span class="font-bold">3:00 PM</span></p>
                                <p class="mt-2">Hours of Stay: <span class="font-bold">3 hours</span></p>
                                <p class="mt-2">Reward: <span class="font-bold">None</span></p>
                                <p class="mt-2">Children (7 below): <span class="font-bold">2</span></p>
                            </div>
                        </div>
                        <div class="mt-5 border-multiStepBoxBorder w-10/12 p-4">
                            <p>Notes: <span class="font-bold">Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat sunt, quasi fugit ipsum, cumque eum, voluptatem animi deleniti nam culpa labore qui laboriosam nulla dicta a non! Laudantium, voluptate tenetur!</span></p>
                        </div>
                    </div>
                    <div class="col-span-1">
                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">Order Set 399</p>
                            <p class="col-span-1 text-center">10x</p>
                            <p class="justify-self-end col-span-1">3990.00 <span class="text-xs">Php</span></p>
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
                            <p class="col-span-1 text-center">2x</p>
                            <p class="justify-self-end col-span-1">200.00 <span class="text-xs">Php</span></p>
                        </div>
                        
                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">Children (7 below)</p>
                            <p class="col-span-1 text-center">0x</p>
                            <p class="justify-self-end col-span-1">0.00 <span class="text-xs">Php</span></p>
                        </div>

                        <div class="bg-sundayToSaturdayBoxColor h-height1Px my-2"></div>

                        <div class="grid grid-cols-2 mt-2">
                            <p class="col-span-1">Total Price</p>
                            <p class="justify-self-end col-span-1"><span class="text-loginLandingPage text-lg font-bold">3790.00</span> <span class="text-xs">Php</span></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="bg-manageRestaurantSidebarColorActive h-8"></div>
        
        {{-- POP UP FORMS --}}
        <div class="declined-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl font-Montserrat">Decline Customer Reservation</h1>
            <form action="" method="POST" id="declinedForm">
                @csrf
                <div class="w-11/12 mx-auto mt-10 text-center">
                    <textarea type="text" name="reason" placeholder="Please type your reason here" class="border rounded-md focus:border-black w-full py-3 px-2 text-sm focus:outline-non text-gray-700" rows="5" required></textarea>
                </div>
                <div class="text-center mt-10">
                    <button class="bg-submitButton text-white hover:bg-darkerSubmitButton hover:text-gray-300 rounded-md w-32 h-10 text-sm uppercase font-bold transition duration-200 ease-in-out" type="submit">Submit</button>
                </div>
            </form>
        </div>
        <div class="overlay"></div>
    </div>
</div>
@endsection