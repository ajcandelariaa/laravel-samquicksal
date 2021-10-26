@extends('restaurant.app3')

@section('content')
<div class="container mx-auto font-Montserrat mb-10 booking">
    <div class="w-11/12 mx-auto mt-10 pb-2 grid grid-cols-2">
        <a href="/restaurant/live-transaction/customer-ordering/list" class="text-submitButton uppercase font-bold"><i class="fas fa-chevron-left mr-2"></i>Back</a>
    </div>
    <div class="w-11/12 mx-auto mt-5 font-Montserrat bg-white">
        <div class="bg-manageRestaurantSidebarColorActive grid grid-cols-2 px-5 py-1 items-center">
            <div class="uppercase font-bold text-white text-xl py-2">ORDER SUMMARY</div>
            <div class="justify-self-end text-white py-3 ml-3">
                <button id="btn-decline" class="px-8 py-2 bg-headerActiveTextColor mr-2">Runaway</button>
                <button id="btn-decline" class="px-8 py-2 bg-postedStatus">Checkout</button>
            </div>
        </div>
        <div class="w-11/12 mx-auto py-10">
            <div class="grid grid-cols-3 gap-5 items-start">

                <div class="col-span-2 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">CUSTOMER DETAILS</div>
                    <div class="grid grid-cols-orderCustDetailsGrid items-center py-7 gap-x-5 w-10/12 mx-auto">
                        <div>
                            <img src="{{ asset('images/user-default.png') }}" alt="customerImage" class="w-24 h-24 rounded-3xl">
                        </div>
                        <div>
                            <p>Name: Bianca Beatrix R. Quicho</p>
                            <p>Order: 399 </p>
                            <p>Table Number: 1</p>
                            <p>Last Request: 4 mins ago</p>
                        </div>
                    </div>
                </div>



                
                <div class="col-span-1 row-span-2 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">DISCOUNT & CHARGES</div>
                    <div class="py-5 w-11/12 mx-auto">
                        <p class="text-center">Please validate Number of Senior Citizen/PWD and Childen below 7.</p>

                        <div class="grid grid-cols-2 items-center mt-10">
                            <p class="col-span-1">Number of Senior</p>
                            <input type="text" name="" class="border focus:border-black py-1 px-2 text-sm focus:outline-non text-gray-700">
                        </div>
                        <div class="grid grid-cols-2 items-center mt-2">
                            <p class="col-span-1">Number of Children</p>
                            <input type="text" name="" class="border focus:border-black py-1 px-2 text-sm focus:outline-non text-gray-700">
                        </div>

                        <hr class="my-6">

                        <div class="grid grid-cols-2 items-center mt-2">
                            <p class="col-span-1">Children Discount</p>
                            <input type="text" name="" class="border focus:border-black py-1 px-2 text-sm focus:outline-non text-gray-700">
                        </div>
                        <div class="grid grid-cols-2 items-center mt-2">
                            <p class="col-span-1">Promo Discount</p>
                            <input type="text" name="" class="border focus:border-black py-1 px-2 text-sm focus:outline-non text-gray-700">
                        </div>
                        <div class="grid grid-cols-2 items-center mt-2">
                            <p class="col-span-1">Offense Charge</p>
                            <input type="text" name="" class="border focus:border-black py-1 px-2 text-sm focus:outline-non text-gray-700">
                        </div>
                        <div class="grid grid-cols-2 items-center mt-2">
                            <p class="col-span-1">Additional Discount</p>
                            <input type="text" name="" class="border focus:border-black py-1 px-2 text-sm focus:outline-non text-gray-700">
                        </div>

                        <div class="text-center">
                            <button class="px-10 py-1 bg-manageRestaurantSidebarColorActive text-white mt-10">Apply</button>
                        </div>
                    </div>
                </div>




                <div class="col-span-2 row-span-5 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">ORDER HISTORY</div>
                    <div class="py-5 w-11/12 mx-auto">
                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">Samgyeop Order Set</p>
                            <p class="col-span-1 text-center">4x</p>
                            <p class="justify-self-end col-span-1 font-bold">1000.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>


                        <hr class="my-3">


                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">Water</p>
                            <p class="col-span-1 text-center">4x</p>
                            <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>
                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">Water</p>
                            <p class="col-span-1 text-center">4x</p>
                            <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>
                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">Water</p>
                            <p class="col-span-1 text-center">4x</p>
                            <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>
                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">Water</p>
                            <p class="col-span-1 text-center">4x</p>
                            <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>
                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">Water</p>
                            <p class="col-span-1 text-center">4x</p>
                            <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>


                        <hr class="my-3">


                        <div class="grid grid-cols-2 mt-2">
                            <p class="col-span-1 text-manageRestaurantSidebarColor">Subtotal</p>
                            <p class="justify-self-end col-span-1 font-bold text-submitButton text-lg">1000.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>
                        <div class="grid grid-cols-2 mt-2">
                            <p class="col-span-1">Service Fee (30%)</p>
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
                            <p class="col-span-1 text-center">4x</p>
                            <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>
                        <div class="grid grid-cols-3 mt-2">
                            <p class="col-span-1">Children (50%)</p>
                            <p class="col-span-1 text-center">4x</p>
                            <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>
                        <div class="grid grid-cols-2 mt-2">
                            <p class="col-span-1">Additional Discount</p>
                            <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>
                        <div class="grid grid-cols-2 mt-2">
                            <p class="col-span-1">Promo Discount</p>
                            <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>


                        <hr class="my-3">

                        
                        <div class="mt-2">
                            <p class="col-span-1 text-manageRestaurantSidebarColor">Charges</p>
                        </div>
                        <div class="grid grid-cols-2 mt-2">
                            <p class="col-span-1">Offense Charges</p>
                            <p class="justify-self-end col-span-1 font-bold">0.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>


                        <hr class="my-3">

                        
                        <div class="grid grid-cols-2 mt-2">
                            <p class="col-span-1 text-manageRestaurantSidebarColor">Total Price</p>
                            <p class="justify-self-end col-span-1 font-bold text-loginLandingPage text-lg">1000.00 <span class="text-xs text-manageRestaurantSidebarColor font-normal">Php</span></p>
                        </div>
                    </div>
                </div>



                <div class="col-span-1 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">GCASH PAYMENT</div>
                    <div class="py-5 w-11/12 mx-auto">
                        <p class="text-center">Please validate if Payment is received.</p>


                        <div class="text-center">
                            <button class="px-10 py-1 bg-manageRestaurantSidebarColorActive text-white mt-10">Download</button>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <div class="bg-manageRestaurantSidebarColorActive h-8"></div>
    </div>
</div>
@endsection