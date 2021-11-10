@extends('restaurant.app5')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 font-Montserrat bg-adminViewAccountHeaderColor2 pb-2">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white text-center text-xl py-3">Customer Offenses</div>
        </div>
        
        <div class="grid grid-rows-1 gap-y-2">
            <div class="grid grid-cols-12 pt-4 pb-2 text-center">
                <div class="col-span-1">No.</div>
                <div class="col-span-3">Customer Name</div>
                <div class="col-span-2">Type</div>
                <div class="col-span-1">Total</div>
                <div class="col-span-1">Maximum</div>
                <div class="col-span-1">Block Days</div>
                <div class="col-span-2">Expiration</div>
                <div class="col-span-1">View</div>
            </div>

            @if ($custMainOffenses != null)
                @php
                    $count = 1
                @endphp
                @for ($i=0; $i<sizeOf($custMainOffenses); $i++)
                    @if ($count % 2 == 0)
                        <div class="grid grid-cols-12 py-2 text-center bg-manageFoodItemHeaderBgColor">
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
                            <div class="col-span-3">{{ $storeName[$i] }}</div>
                            <div class="col-span-2">{{ $custMainOffenses[$i]['offenseType'] }}</div>
                            <div class="col-span-1">{{ $custMainOffenses[$i]['totalOffense'] }}</div>
                            <div class="col-span-1">{{ $custMainOffenses[$i]['offenseCapacity'] }}</div>
                            <div class="col-span-1">{{ $custMainOffenses[$i]['offenseDaysBlock'] }}</div>
                            <div class="col-span-2">{{ $storeValidity[$i] }}</div>
                            <div class="col-span-1">
                                <a href="#">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-12 py-2 text-center bg-white">
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
                            <div class="col-span-3">{{ $storeName[$i] }}</div>
                            <div class="col-span-2">{{ $custMainOffenses[$i]['offenseType'] }}</div>
                            <div class="col-span-1">{{ $custMainOffenses[$i]['totalOffense'] }}</div>
                            <div class="col-span-1">{{ $custMainOffenses[$i]['offenseCapacity'] }}</div>
                            <div class="col-span-1">{{ $custMainOffenses[$i]['offenseDaysBlock'] }}</div>
                            <div class="col-span-2">{{ $storeValidity[$i] }}</div>
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

    @if ($custMainOffenses != null)
        <div class="mx-auto w-11/12 mt-6">
            {{ $custMainOffenses->links() }}
        </div>
    @endif

    @if ($custMainOffenses == null)
        <div class="mx-auto w-11/12 mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 shadow-xl rounded-lg">
            <div class="text-center text-multiStepBoxColor uppercase">There are no customer offenses as of now</div>
        </div>
    @endif
</div>
@endsection