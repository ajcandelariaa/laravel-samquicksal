@extends('restaurant.app3')

@section('content')
<div class="container mx-auto font-Montserrat mb-10 booking">
    <div class="w-11/12 mx-auto mt-10 pb-2 grid grid-cols-2">
        <a href="/restaurant/live-transaction/customer-ordering/list" class="text-submitButton uppercase font-bold"><i class="fas fa-chevron-left mr-2"></i>Back</a>
    </div>
    <div class="w-11/12 mx-auto mt-5 font-Montserrat bg-white">
        <div class="bg-manageRestaurantSidebarColorActive grid grid-cols-2 px-5 py-1 items-center">
            <div class="uppercase font-bold text-white text-xl py-2">ORDERS & REQUESTS</div>
            <div class="justify-self-end text-white py-3 ml-3">
                @if ($customerOrdering->grantedAccess == "No")
                    <button id="btn-decline" class="px-8 py-2 bg-postedStatus">Grant Access</button>
                @endif
            </div>
        </div>
        <div class="w-11/12 mx-auto py-10">
            <div class="grid grid-cols-2 gap-x-3 items-start">
                <div class="col-span-1 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">Customer DETAILS</div>
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
                
                <div class="col-span-1 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">CURRENT CUSTOMER REQUEST (4)</div>
                    <div class="py-5 w-10/12 mx-auto">
                        <div class="grid grid-cols-2 items-center">
                            <p class="col-span-1">Table 1: Call A Waiter</p>
                            <button class="px-4 py-1 bg-postedStatus col-span-1 justify-self-end text-white">Request Done</button>
                        </div>
                        <hr class="my-4 border-solid border-gray-500">
                        <div class="grid grid-cols-2 items-center">
                            <p class="col-span-1">Table 1: Call A Waiter</p>
                            <button class="px-4 py-1 bg-postedStatus col-span-1 justify-self-end text-white">Request Done</button>
                        </div>
                        <hr class="my-4 border-solid border-gray-500">
                    </div>
                </div>
            </div>


            <form action="">
                <div class="grid grid-cols-2 mt-10 items-center">
                    <div class="uppercase font-bold text-lg py-2 col-span-1">CURRENT CUSTOMER ORDER (10)</div>
                    <div class="justify-self-end col-span-1">
                        <span class="text-xs text-gray-500 mr-4 italic">Click this button once you are done serving</span>
                        <button class="px-12 py-1 bg-postedStatus text-white">Serve</button>
                    </div>
                </div>

                <div class="bg-adminViewAccountHeaderColor2 grid grid-rows-1 gap-y-2 mt-3 pt-2 pb-4 px-1 shadow-adminDownloadButton">
                    <div class="grid grid-cols-6 py-2 text-center px-3 items-center">
                        <div class="col-span-1 bg-white py-2 w-10/12 mx-auto">
                            <input type="checkbox" name="select-all" id="select-all" class="mr-2"> 
                            <label class="text-manageRestaurantSidebarColorActive">Select All</label>
                        </div>
                        <div class="col-span-1">No.</div>
                        <div class="col-span-1">Food Name</div>
                        <div class="col-span-1">Quantity</div>
                        <div class="col-span-1">Price</div>
                        <div class="col-span-1">Table No.</div>
                    </div>


                    <div class="bg-manageFoodItemHeaderBgColor grid grid-cols-6 text-center px-3 items-center">
                        <div class="col-span-1 py-2">
                            <input type="checkbox" name="select-all" id="select-all" class="mr-2 inline"> 
                            <img src="{{ asset('images/user-default.png') }}" alt="foodItem" class="w-10 h-10 inline">
                        </div>
                        <div class="col-span-1">1</div>
                        <div class="col-span-1">Plain Pork</div>
                        <div class="col-span-1">2x</div>
                        <div class="col-span-1">0</div>
                        <div class="col-span-1">1</div>
                    </div>

                    <div class="bg-white grid grid-cols-6 text-center px-3 items-center">
                        <div class="col-span-1 py-2">
                            <input type="checkbox" name="select-all" id="select-all" class="mr-2 inline"> 
                            <img src="{{ asset('images/user-default.png') }}" alt="foodItem" class="w-10 h-10 inline">
                        </div>
                        <div class="col-span-1">1</div>
                        <div class="col-span-1">Plain Pork</div>
                        <div class="col-span-1">2x</div>
                        <div class="col-span-1">0</div>
                        <div class="col-span-1">1</div>
                    </div>


                </div>
            </form>


            <div class="uppercase font-bold text-lg py-2 col-span-1 mt-10">CUSTOMER WITH QR ACCESS</div>
            <div class="grid grid-cols-2 gap-x-3 items-start">
                <div class="col-span-1 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">Customer DETAILS</div>
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
                
                <div class="col-span-1 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">Customer DETAILS</div>
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
            </div>

        </div>
        <div class="bg-manageRestaurantSidebarColorActive h-8"></div>
    </div>
</div>
@endsection