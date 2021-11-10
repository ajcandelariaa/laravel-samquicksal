@extends('restaurant.app5')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 font-Montserrat bg-adminViewAccountHeaderColor2 pb-2">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white text-center text-xl py-3">Stamp Cards History</div>
        </div>
        
        <div class="grid grid-rows-1 gap-y-2">
            <div class="grid grid-cols-9 pt-4 pb-2 text-center">
                <div class="col-span-1">No.</div>
                <div class="col-span-3">Stamp Capacity</div>
                <div class="col-span-2">Stamp Reward</div>
                <div class="col-span-2">Stamp Validity</div>
                <div class="col-span-1">View</div>
            </div>

            
            @if ($storeStamps != null)
                @php
                    $count = 1
                @endphp
                @for ($i=0; $i<sizeOf($storeStamps); $i++)
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
                            <div class="col-span-3">{{ $storeStamps[$i]['stampCapacity'] }}</div>
                            <div class="col-span-2">{{ $storeStamps[$i]['finalReward'] }}</div>
                            <div class="col-span-2">{{ $storeStamps[$i]['stampValidity'] }}</div>
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
                            <div class="col-span-3">{{ $storeStamps[$i]['stampCapacity'] }}</div>
                            <div class="col-span-2">{{ $storeStamps[$i]['finalReward'] }}</div>
                            <div class="col-span-2">{{ $storeStamps[$i]['stampValidity'] }}</div>
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

    @if ($stampCards != null)
        <div class="mx-auto w-11/12 mt-6">
            {{ $stampCards->links() }}
        </div>
    @endif

    @if ($stampCards == null)
        <div class="mx-auto w-11/12 mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 shadow-xl rounded-lg">
            <div class="text-center text-multiStepBoxColor uppercase">There are no stamp card as of now</div>
        </div>
    @endif
</div>
@endsection