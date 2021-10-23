@extends('restaurant.app3')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 font-Montserrat bg-adminViewAccountHeaderColor2 pb-2">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white text-center text-xl py-3">BOOK FOR QUEUE-IN</div>
        </div>
        
        <div class="grid grid-rows-1 gap-y-2">
            <div class="grid grid-cols-10 pt-4 pb-2 text-center">
                <div class="col-span-1">No.</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-2">Name</div>
                <div class="col-span-1">Persons</div>
                <div class="col-span-1">Tables</div>
                <div class="col-span-1">Priority Persons</div>
                <div class="col-span-2">Cancellation Time Left</div>
                <div class="col-span-1">View</div>
            </div>
    
            <div class="grid grid-cols-10 py-2 text-center bg-manageFoodItemHeaderBgColor">
                <div class="col-span-1">1</div>
                <div class="col-span-1">Pending</div>
                <div class="col-span-2">Aj Candelaria</div>
                <div class="col-span-1">10</div>
                <div class="col-span-1">3</div>
                <div class="col-span-1">3</div>
                <div class="col-span-2">15 minutes</div>
                <div class="col-span-1">
                    <a href="/restaurant/live-transaction/customer-booking/queue/1">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
    
            <div class="grid grid-cols-10 py-2 text-center bg-white">
                <div class="col-span-1">1</div>
                <div class="col-span-1">Pending</div>
                <div class="col-span-2">Aj Candelaria</div>
                <div class="col-span-1">10</div>
                <div class="col-span-1">3</div>
                <div class="col-span-1">3</div>
                <div class="col-span-2">15 minutes</div>
                <div class="col-span-1">
                    <a href="/restaurant/live-transaction/customer-booking/queue/1">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection