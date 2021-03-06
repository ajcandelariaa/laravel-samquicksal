@extends('restaurant.app5')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 font-Montserrat bg-adminViewAccountHeaderColor2 pb-2">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white text-center text-xl py-3">Customer Stamp Card History</div>
        </div>
        
        <div class="grid grid-rows-1 gap-y-2">
            <div class="grid grid-cols-11 pt-4 pb-2 text-center">
                <div class="col-span-1">No.</div>
                <div class="col-span-2">Name</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-1">Claimed</div>
                <div class="col-span-2">Stamp Reward</div>
                <div class="col-span-2">Stamp Validity</div>
                <div class="col-span-1">View</div>
            </div>

            
            @if ($storeCustStampCards != null)
                @php
                    $count = 1
                @endphp
                @for ($i=0; $i<sizeOf($storeCustStampCards); $i++)
                    @if ($count % 2 == 0)
                        <div class="grid grid-cols-11 py-2 text-center bg-manageFoodItemHeaderBgColor">
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
                            <div class="col-span-2">{{ $storeCustStampCards[$i]['custName'] }}</div>
                            <div class="col-span-2">{{ $storeCustStampCards[$i]['stampStatus'] }}</div>
                            <div class="col-span-1">{{ $storeCustStampCards[$i]['stampClaimed'] }}</div>
                            <div class="col-span-2">{{ $storeCustStampCards[$i]['stampReward'] }}</div>
                            <div class="col-span-2">{{ $storeCustStampCards[$i]['stampValidity'] }}</div>
                            <div class="col-span-1">
                                <a href="/restaurant/stamp-offenses/customer-stamp-history/{{ $storeCustStampCards[$i]['stamp_id'] }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-11 py-2 text-center bg-white">
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
                            <div class="col-span-2">{{ $storeCustStampCards[$i]['custName'] }}</div>
                            <div class="col-span-2">{{ $storeCustStampCards[$i]['stampStatus'] }}</div>
                            <div class="col-span-1">{{ $storeCustStampCards[$i]['stampClaimed'] }}</div>
                            <div class="col-span-2">{{ $storeCustStampCards[$i]['stampReward'] }}</div>
                            <div class="col-span-2">{{ $storeCustStampCards[$i]['stampValidity'] }}</div>
                            <div class="col-span-1">
                                <a href="/restaurant/stamp-offenses/customer-stamp-history/{{ $storeCustStampCards[$i]['stamp_id'] }}">
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

    @if ($storeCustStampCards != null)
        <div class="mx-auto w-11/12 mt-6">
            {{ $custStampCards->links() }}
        </div>
    @endif

    @if ($storeCustStampCards == null)
        <div class="mx-auto w-11/12 mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 shadow-xl rounded-lg">
            <div class="text-center text-multiStepBoxColor uppercase">There are no customer stamp card as of now</div>
        </div>
    @endif
</div>
@endsection