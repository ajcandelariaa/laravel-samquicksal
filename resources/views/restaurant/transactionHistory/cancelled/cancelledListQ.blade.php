@extends('restaurant.app4')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 font-Montserrat bg-adminViewAccountHeaderColor2 pb-2">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white text-center text-xl py-3">Cancelled Customer Queue</div>
        </div>
        
        <div class="grid grid-rows-1 gap-y-2">
            <div class="grid grid-cols-9 pt-4 pb-2 text-center">
                <div class="col-span-1">No.</div>
                <div class="col-span-3">Name</div>
                <div class="col-span-2">Book Date</div>
                <div class="col-span-2">Book Time</div>
                <div class="col-span-1">View</div>
            </div>

            
            @if ($storeCustomer != null)
                @php
                    $count = 1
                @endphp
                @for ($i=0; $i<sizeOf($storeCustomer); $i++)
                    @if ($count % 2 == 0)
                        <div class="grid grid-cols-9 py-2 text-center bg-manageFoodItemHeaderBgColor">
                            <div class="col-span-1">
                                @if (isset($_GET['page']))
                                    @if ($_GET['page'] == 1)
                                        {{ $count }}
                                    @else
                                        {{ ((($_GET['page'] - 1) * 10) + $count) }}
                                    @endif
                                @else
                                    {{ $count }}
                                @endif
                            </div>
                            <div class="col-span-3">{{ $storeCustomer[$i]['custName'] }}</div>
                            <div class="col-span-2">{{ $storeCustomer[$i]['bookDate'] }}</div>
                            <div class="col-span-2">{{ $storeCustomer[$i]['bookTime'] }}</div>
                            <div class="col-span-1">
                                <a href="#">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-9 py-2 text-center bg-white">
                            <div class="col-span-1">
                                @if (isset($_GET['page']))
                                    @if ($_GET['page'] == 1)
                                        {{ $count }}
                                    @else
                                        {{ ((($_GET['page'] - 1) * 10) + $count) }}
                                    @endif
                                @else
                                    {{ $count }}
                                @endif
                            </div>
                            <div class="col-span-3">{{ $storeCustomer[$i]['custName'] }}</div>
                            <div class="col-span-2">{{ $storeCustomer[$i]['bookDate'] }}</div>
                            <div class="col-span-2">{{ $storeCustomer[$i]['bookTime'] }}</div>
                            <div class="col-span-1">
                                <a href="#">
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

    @if ($storeCustomer != null)
        <div class="mx-auto w-11/12 mt-6">
            {{ $customerQueue->links() }}
        </div>
    @endif

    @if ($storeCustomer == null)
        <div class="mx-auto w-11/12 mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 shadow-xl rounded-lg">
            <div class="text-center text-multiStepBoxColor uppercase">There are no queue cancelled customer as of now</div>
        </div>
    @endif
</div>
@endsection