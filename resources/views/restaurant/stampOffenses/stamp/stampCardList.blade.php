@extends('restaurant.app5')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 font-Montserrat bg-white">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white text-center text-xl py-3">Stamp Cards History</div>
        </div>
        @if ($storeStamps != null)
            <div class="grid grid-cols-2 py-5 gap-x-5 w-11/12 mx-auto">
                @for ($i=0; $i<sizeOf($storeStamps); $i++)
                    <div class="col-span-1 bg-manageFoodItemHeaderBgColor shadow-adminDownloadButton">
                        <div class="bg-adminViewAccountHeaderColor2">
                            <div class="uppercase font-bold text-center text-md py-2">{{ $storeStamps[$i]['stampValidity'] }}</div>
                        </div>
                        <div class="py-5">
                            <div class="grid grid-cols-2 w-11/12 mx-auto gap-x-4">
                                <div class="text-submitButton font-bold">
                                    <p class="mt-3">Stamp Capacity</p>
                                    <hr>
                                    <p class="mt-3">Reward</p>
                                    <hr>
                                    <p class="mt-3">Tasks</p>
                                </div>
                                <div>
                                    <p class="mt-3">{{ $storeStamps[$i]['stampCapacity'] }}</p>
                                    <hr>
                                    <p class="mt-3">{{ $storeStamps[$i]['finalReward'] }}</p>
                                    <hr>
                                    <div>
                                        @foreach ($storeStamps[$i]['storeTasks'] as $storeTask)
                                            <p class="mt-1">{{ $storeTask }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="bg-manageRestaurantSidebarColorActive py-5"></div>
        @endif
    </div>

    @if ($storeStamps != null)
        <div class="mx-auto w-11/12 mt-6">
            {{ $stampCards->links() }}
        </div>
    @endif

    @if ($storeStamps == null)
        <div class="mx-auto w-11/12 mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 shadow-xl rounded-lg">
            <div class="text-center text-multiStepBoxColor uppercase">There are no stamp card as of now</div>
        </div>
    @endif
</div>
@endsection