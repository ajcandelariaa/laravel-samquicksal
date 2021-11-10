@extends('restaurant.app3')

@section('content')
<div class="container mx-auto">
    @if (session()->has('completed'))
        <script>
            Swal.fire(
                'Customer Completed',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('runaway'))
        <script>
            Swal.fire(
                'Customer Runaway',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10 font-Montserrat bg-white pb-2 mb-10">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white text-center text-xl py-3">Table Status</div>
        </div>

        <div class="text-center grid grid-cols-2 mt-6 w-96 mx-auto">
            <div class="flex items-center">
                <div class="rounded-full w-5 h-5 bg-manageRestaurantSidebarColor mr-2"></div>
                <p>Unoccupied</p>
            </div>
            <div class="flex items-center">
                <div class="rounded-full w-5 h-5 bg-headerActiveTextColor mr-2"></div>
                <p>Occupied</p>
            </div>
        </div>
        
        <div class="grid grid-cols-4 gap-5 mx-5 my-10">
            @foreach ($finalCustomers as $finalCustomer)
                <div class="col-span-1 {{ ($finalCustomer['custOrdering_id'] == "") ? 'bg-manageRestaurantSidebarColor' : 'bg-headerActiveTextColor' }} rounded-2xl items-stretch">
                    <div class="grid grid-rows-customerOrderingTableRow text-center pt-3 pb-1 text-white">
                        <p class="row-span-1">
                            @if ($finalCustomer['custOrdering_id'] != "")
                                <i class="fas fa-clock mr-3"></i>02h 02m 00s</span>
                            @endif
                        </p>
                        @if ($finalCustomer['custOrdering_id'] == "")   
                            <p class="row-span-1 font-bold text-6xl font-Roboto py-10 cursor-default">{{ $finalCustomer['tableNumber'] }}</p>
                        @else
                            <a href="/restaurant/live-transaction/customer-ordering/list/{{ $finalCustomer['custOrdering_id'] }}/order-request" class="row-span-1 font-bold text-6xl font-Roboto py-10 hover:underline ">{{ $finalCustomer['tableNumber'] }}</a>
                        @endif
                        <div class="row-span-1">
                            @if ($finalCustomer['custOrdering_id'] != "")
                                <div class="grid grid-cols-3">
                                    <p>Order</p>
                                    <p>Request</p>
                                    <p>Access</p>
                                </div>
                                <div class="grid grid-cols-3 justify-self-end font-Roboto">
                                    <p>{{ $finalCustomer['countOrders'] }}</p>
                                    <p>{{ $finalCustomer['countRequests'] }}</p>
                                    <p>{{ $finalCustomer['grantedAccess'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection