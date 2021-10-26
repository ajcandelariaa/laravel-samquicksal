@extends('restaurant.app3')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 font-Montserrat bg-adminViewAccountHeaderColor2 pb-2">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white text-center text-xl py-3">CUSTOMER ORDERING</div>
        </div>
        
        <div class="grid grid-rows-1 gap-y-2">
            <div class="grid grid-cols-10 pt-4 pb-2 text-center">
                <div class="col-span-1">No.</div>
                <div class="col-span-2">Table Number/s</div>
                <div class="col-span-2">Customer Name</div>
                <div class="col-span-1">Order</div>
                <div class="col-span-1">Request</div>
                <div class="col-span-2">Granted Access</div>
                <div class="col-span-1">View</div>
            </div>

            @if (!$eatingCustomers->isEmpty())
                @php
                    $count = 1
                @endphp
                @for ($i=0; $i<sizeOf($eatingCustomers); $i++)
                    @if ($count % 2 == 0)
                        <div class="grid grid-cols-10 py-2 text-center bg-manageFoodItemHeaderBgColor">
                            <div class="col-span-1">{{ $count }}</div>
                            <div class="col-span-2">{{ $eatingCustomers[$i]->tableNumbers }}</div>
                            <div class="col-span-2">{{ $eatingCustomers[$i]->custName }}</div>
                            <div class="col-span-1">{{ $orders[$i] }}</div>
                            <div class="col-span-1">{{ $requests[$i] }}</div>
                            <div class="col-span-2">{{ $eatingCustomers[$i]->grantedAccess }}</div>
                            <div class="col-span-1">
                                <a href="/restaurant/live-transaction/customer-ordering/list/{{ $eatingCustomers[$i]->id }}/order-request">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-10 py-2 text-center bg-white">
                            <div class="col-span-1">{{ $count }}</div>
                            <div class="col-span-2">{{ $eatingCustomers[$i]->tableNumbers }}</div>
                            <div class="col-span-2">{{ $eatingCustomers[$i]->custName }}</div>
                            <div class="col-span-1">{{ $orders[$i] }}</div>
                            <div class="col-span-1">{{ $requests[$i] }}</div>
                            <div class="col-span-2">{{ $eatingCustomers[$i]->grantedAccess }}</div>
                            <div class="col-span-1">
                                <a href="/restaurant/live-transaction/customer-ordering/list/{{ $eatingCustomers[$i]->id }}/order-request">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                    @php
                        $count++
                    @endphp
                @endfor
            @endif
        </div>
    </div>
    
    @if ($eatingCustomers->isEmpty())
        <div class="mx-auto w-11/12 mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 shadow-xl rounded-lg">
            <div class="text-center text-multiStepBoxColor uppercase">There are no ordering customer as of now</div>
        </div>
    @endif
</div>
@endsection