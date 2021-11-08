@extends('restaurant.app3')

@section('content')
<div class="container mx-auto font-Montserrat mb-10 booking">
    @if (session()->has('grantedAccess'))
        <script>
            Swal.fire(
                'Granted Access',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('selectAtLeastOne'))
        <script>
            Swal.fire(
                'Please select at least one item',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('servedComplete'))
        <script>
            Swal.fire(
                'Served Complete',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('requestDone'))
        <script>
            Swal.fire(
                'Request Complete',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10 pb-2 grid grid-cols-2">
        <a href="/restaurant/live-transaction/customer-ordering/list" class="text-submitButton uppercase font-bold"><i class="fas fa-chevron-left mr-2"></i>Back</a>
    </div>
    <div class="w-11/12 mx-auto mt-5 font-Montserrat bg-white">
        <div class="bg-manageRestaurantSidebarColorActive grid grid-cols-2 px-5 py-1 items-center">
            <div class="uppercase font-bold text-white text-xl py-2">ORDERS & REQUESTS</div>
            <div class="justify-self-end text-white py-3 ml-3">
                @if ($customerOrdering->grantedAccess == "No")
                    <a href="/restaurant/live-transaction/customer-ordering/list/{{ $customerOrdering->id }}/order-request/grant-access" id="btn-access" class="px-8 py-2 bg-postedStatus">Grant Access</a>
                @endif
            </div>
        </div>
        <div class="w-11/12 mx-auto py-10">
            <div class="grid grid-cols-2 gap-x-3 items-start">
                <div class="col-span-1 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">Customer DETAILS</div>
                    <div class="grid grid-cols-orderCustDetailsGrid items-center py-7 gap-x-5 w-11/12 mx-auto">
                        <div class="self-start">
                            @if ($customer->profileImage == null)
                                <img src="{{ asset('images/user-default.png') }}" alt="customerImage" class="w-24 h-24 rounded-3xl start">
                            @else
                                <img src="{{ asset('uploads/customerAccounts/logo/'.$customer->id.'/'.$customer->profileImage) }}" alt="customerImage" class="w-24 h-24 rounded-3xl start">
                            @endif
                        </div>
                        <div class="grid grid-cols-2">
                            <p>Name: {{ $customerOrdering->custName }}</p>
                            <p>Main Table: {{ $mainTable }} </p>
                            <p>Order Set: {{ $order }} </p>
                            <p>Table Number/s: {{ $customerOrdering->tableNumbers }}</p>
                            <p>Last Order: {{ $rAppDiffTime }}</p>
                            <p>Last Request: {{ $rAppDiffTime2 }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-span-1 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                    <div class="uppercase font-bold text-center text-lg py-2 bg-adminViewAccountHeaderColor2">CURRENT CUSTOMER REQUEST</div>
                    <div class="py-5 w-10/12 mx-auto">
                        @if ($customerRequests->isEmpty())
                            <div class="text-center text-multiStepBoxColor uppercase">There are no pending request as of now</div>
                        @else
                            @foreach ($customerRequests as $customerRequest)
                                <div class="grid grid-cols-2 items-center">
                                    <p class="col-span-1">Table {{ $customerRequest->tableNumber }}: {{ $customerRequest->request }}</p>
                                    <a href="/restaurant/live-transaction/customer-ordering/list/{{ $customerOrdering->id }}/order-request/request-done/{{ $customerRequest->id }}" class="underline text-purple-500 hover:text-purple-900 justify-self-end">Request Done</a>
                                </div>
                                <hr class="my-2 border-solid border-gray-500">
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>


            <form action="/restaurant/live-transaction/customer-ordering/list/{{ $customerOrdering->id }}/order-request" method="POST" id="serveForm">
                @csrf
                <div class="grid grid-cols-2 mt-10 items-center">
                    <div class="uppercase font-bold text-lg py-2 col-span-1">CURRENT CUSTOMER ORDER</div>
                    <div class="justify-self-end col-span-1">
                        @if (!$customerOrders->isEmpty())
                            <span class="text-xs text-gray-500 mr-4 italic">Click this button once you are done serving</span>
                            <button class="px-12 py-1 bg-postedStatus text-white">Serve</button>
                        @endif
                    </div>
                </div>

                <div class="bg-adminViewAccountHeaderColor2 grid grid-rows-1 gap-y-2 mt-3 pt-2 pb-4 px-1 shadow-adminDownloadButton">
                    <div class="grid grid-cols-6 py-2 text-center px-3 items-center">
                        <div class="col-span-1 bg-white py-2 w-10/12 mx-auto">
                            <input type="checkbox" name="select-all" id="select-all" class="mr-2" {{ ($customerOrders->isEmpty()) ? 'disabled' : ''}}> 
                            <label class="text-manageRestaurantSidebarColorActive">Select All</label>
                        </div>
                        <div class="col-span-1">No.</div>
                        <div class="col-span-1">Food Name</div>
                        <div class="col-span-1">Quantity</div>
                        <div class="col-span-1">Price</div>
                        <div class="col-span-1">Table No.</div>
                    </div>

                    @if ($customerOrders->isEmpty())
                        <div class="mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 rounded-lg">
                            <div class="text-center text-multiStepBoxColor uppercase">There are no pending orders as of now</div>
                        </div>
                    @else
                        @php
                            $count = 1;
                        @endphp
                        @for ($i=0; $i<sizeOf($finalCustomerOrders); $i++)
                            @if ($count % 2 == 0)
                                <div class="bg-manageFoodItemHeaderBgColor grid grid-cols-6 text-center px-3 items-center">
                                    <div class="col-span-1 py-2">
                                        <input type="checkbox" name="order[]" value="{{ $finalCustomerOrders[$i]['orderId'] }}" class="mr-2 inline"> 
                                        <img src="{{ asset('uploads/restaurantAccounts/foodItem/'.$restAcc_id.'/'.$finalCustomerOrders[$i]['foodItemImage']) }}" alt="foodItem" class="w-10 h-10 inline">
                                    </div>
                                    <div class="col-span-1">{{ $count }}</div>
                                    <div class="col-span-1">{{ $finalCustomerOrders[$i]['foodItemName'] }}</div>
                                    <div class="col-span-1">{{ $finalCustomerOrders[$i]['quantity'] }}x</div>
                                    <div class="col-span-1">{{ $finalCustomerOrders[$i]['price'] }}</div>
                                    <div class="col-span-1">{{ $finalCustomerOrders[$i]['tableNumber'] }}</div>
                                </div>
                            @else
                                <div class="bg-white grid grid-cols-6 text-center px-3 items-center">
                                    <div class="col-span-1 py-2">
                                        <input type="checkbox" name="order[]" value="{{ $finalCustomerOrders[$i]['orderId'] }}" class="mr-2 inline"> 
                                        <img src="{{ asset('uploads/restaurantAccounts/foodItem/'.$restAcc_id.'/'.$finalCustomerOrders[$i]['foodItemImage']) }}" alt="foodItem" class="w-10 h-10 inline">
                                    </div>
                                    <div class="col-span-1">{{ $count }}</div>
                                    <div class="col-span-1">{{ $finalCustomerOrders[$i]['foodItemName'] }}</div>
                                    <div class="col-span-1">{{ $finalCustomerOrders[$i]['quantity'] }}x</div>
                                    <div class="col-span-1">{{ $finalCustomerOrders[$i]['price'] }}</div>
                                    <div class="col-span-1">{{ $finalCustomerOrders[$i]['tableNumber'] }}</div>
                                </div>
                            @endif
                        @php
                            $count++
                        @endphp
                        @endfor
                    @endif
                </div>
            </form>


            <div class="uppercase font-bold text-lg py-2 col-span-1 mt-10">CUSTOMER WITH QR ACCESS</div>
            <div class="grid grid-cols-2 gap-x-3 items-start">
                @if ($finalCustomerAccess == null)
                    <div class="col-span-2 mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 rounded-lg">
                        <div class="text-center text-multiStepBoxColor uppercase">There are no sub customers as of now</div>
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
        <div class="bg-manageRestaurantSidebarColorActive h-8"></div>
    </div>
</div>
@endsection