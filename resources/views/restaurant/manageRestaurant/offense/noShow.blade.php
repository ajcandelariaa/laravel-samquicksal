@extends('restaurant.app2')

@section('content')
<div class="container mx-auto stamp-card">
    @if (session()->has('edited'))
        <script>
            Swal.fire(
                'Offense Updated',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10">
        <div class="grid grid-cols-2 gap-x-5 items-start">
            <div class="col-span-1 bg-manageRestaurantSidebarColorActive pb-8 shadow-adminDownloadButton">
                <form action="/restaurant/manage-restaurant/offense/no-show/edit/{{ $noShow->id }}" method="POST">
                @csrf
                    <div class="w-11/12 mx-auto">
                        <div class="uppercase font-bold text-white text-xl py-3">No Show Offense</div>
                    </div>
                    <div class="bg-white">
                        <div class="grid grid-cols-stampCardGrid w-9/12 gap-y-5 mx-auto py-10">
                            <div class="text-left text-submitButton font-bold">No. of No Show</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <input type="text" name="numberOfNoShow" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                <span class="mt-2 text-red-600 italic text-sm">@error('numberOfNoShow'){{ $message }}@enderror</span>
                            </div>

                            
                            <div class="text-left text-submitButton font-bold">No. of Days</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <input type="text" name="numberOfDays" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                <span class="mt-2 text-red-600 italic text-sm">@error('numberOfDays'){{ $message }}@enderror</span>
                            </div>

                            <div class="text-left text-submitButton font-bold">Type of Offense</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <select name="offenseType" id="offenseType" onchange="noShowBlockDays();" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                    <option value="" selected="selected">--Choose Offense--</option>
                                    <option value="noOffense">No Offense</option>
                                    <option value="permanentBlock">Permanent Block</option>
                                    <option value="limitBlock">Limited Day/s of Block</option>
                                </select>
                                <span class="mt-2 text-red-600 italic text-sm">@error('offenseType'){{ $message }}@enderror</span>
                                
                                <input type="text" placeholder="Number of Days Of Block (e.g. 2)" id="showCancellationBlockDays" style="display: none" name="cancelBlockDays" class="mt-2 w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                <span class="mt-2 text-red-600 italic text-sm">@error('cancelBlockDays'){{ $message }}@enderror</span>
                            </div>

                            
                            <div class="text-center mt-4 col-span-full">
                                <button type="submit" class="bg-submitButton text-white w-36 h-9 rounded-md">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-span-1 bg-manageRestaurantSidebarColorActive pb-8 shadow-adminDownloadButton">
                <div class="w-11/12 mx-auto">
                    <div class="uppercase font-bold text-white text-xl py-3">Status</div>
                </div>
                <div class="bg-adminViewAccountHeaderColor2 grid grid-cols-1 gap-y-2">
                    <div class="py-3"></div>
                    <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                        <p class="col-span-1 text-submitButton font-bold">No. of Cancellation</p>
                        <p class="col-span-1 place-self-end">{{ $noShow->noOfNoShows }}</p>
                    </div>
                    <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                        <p class="col-span-1 text-submitButton font-bold">No. of Days</p>
                        <p class="col-span-1 place-self-end">{{ $noShow->noOfDays }}</p>
                    </div>
                    <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                        <p class="col-span-1 text-submitButton font-bold">Offense Type</p>
                        <p class="col-span-1 place-self-end">
                            @if ($noShow->blockDays == "0")
                                No Offense
                            @elseif ($noShow->blockDays == "Permanent")
                                Permanent Block
                            @else
                                {{ $noShow->blockDays }} day/s of Block
                            @endif
                        </p>
                    </div>
                    <div class="py-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection