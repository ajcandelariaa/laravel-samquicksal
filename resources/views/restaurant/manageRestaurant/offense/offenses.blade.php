@extends('restaurant.app2')

@section('content')
<div class="container mx-auto stamp-card mb-20">
    @if (session()->has('edited'))
        <script>
            Swal.fire(
                'Offense Updated',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10 font-Montserrat">
        <div class="grid grid-cols-2 gap-x-5 items-start">
            <div class="col-span-1 bg-manageRestaurantSidebarColorActive pb-8 shadow-adminDownloadButton">
                <form action="/restaurant/manage-restaurant/offense/cancel-reservation/edit/{{ $cancellation->id }}" id="cancel-offense-form" method="POST">
                @csrf
                    <div class="w-11/12 mx-auto">
                        <div class="uppercase font-bold text-white text-xl py-3">Cancel Offense</div>
                    </div>
                    <div class="bg-white">
                        <div class="grid grid-cols-stampCardGrid w-9/12 gap-y-5 mx-auto py-10">
                            <div class="text-left text-submitButton font-bold">No. of Cancellation</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <input type="number" min="1" name="numberOfCancellation" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black py-1">
                                <span class="mt-2 text-red-600 italic text-sm">@error('numberOfCancellation'){{ $message }}@enderror</span>
                            </div>

                            <div class="text-left text-submitButton font-bold">Type of Offense</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <select name="offenseTypeC" id="offenseTypeC" onchange="cancellationBlockDays();" class="w-full py-1 border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                    <option value="" selected="selected">--Choose Offense--</option>
                                    <option value="noOffense">No Offense</option>
                                    <option value="permanentBlock">Permanent Block</option>
                                    <option value="limitBlock">Limited Day/s of Block</option>
                                </select>
                                <span class="mt-2 text-red-600 italic text-sm">@error('offenseTypeC'){{ $message }}@enderror</span>
                                
                                <input type="number" min="1" placeholder="Number of Days Of Block (e.g. 2)" id="showCancellationBlockDays" style="display: none" name="cancelBlockDays" class="mt-2 w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black py-1">
                                <span class="mt-2 text-red-600 italic text-sm">@error('cancelBlockDays'){{ $message }}@enderror</span>
                            </div>

                            
                            <div class="text-center mt-4 col-span-full">
                                <button id="btn-cancel-offense" type="submit" class="bg-submitButton text-white w-36 h-9 rounded-md font-Montserrat hover:bg-btnHoverColor transition duration-200 ease-in-out ">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-span-1 bg-manageRestaurantSidebarColorActive pb-8 shadow-adminDownloadButton">
                <div class="w-11/12 mx-auto">
                    <div class="uppercase font-bold text-white text-xl py-3">Cancel Offense Status</div>
                </div>
                <div class="bg-adminViewAccountHeaderColor2 grid grid-cols-1 gap-y-2">
                    <div class="py-3"></div>
                    <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                        <p class="col-span-1 text-submitButton font-bold">No. of Cancellation</p>
                        <p class="col-span-1 place-self-end">{{ $cancellation->noOfCancellation }}</p>
                    </div>
                    <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                        <p class="col-span-1 text-submitButton font-bold">Offense Type</p>
                        <p class="col-span-1 place-self-end">
                            @if ($cancellation->blockDays == "0")
                                No Offense
                            @elseif ($cancellation->blockDays == "Permanent")
                                Permanent Block
                            @else
                                {{ $cancellation->blockDays }} day/s of Block
                            @endif
                        </p>
                    </div>
                    <div class="py-2"></div>
                </div>
            </div>
        </div>




        <div class="grid grid-cols-2 gap-x-5 mt-16 items-start">
            <div class="col-span-1 bg-manageRestaurantSidebarColorActive pb-8 shadow-adminDownloadButton">
                <form action="/restaurant/manage-restaurant/offense/no-show/edit/{{ $noShow->id }}" id="noShow-offense-form"  method="POST">
                @csrf
                    <div class="w-11/12 mx-auto">
                        <div class="uppercase font-bold text-white text-xl py-3">No Show Offense</div>
                    </div>
                    <div class="bg-white">
                        <div class="grid grid-cols-stampCardGrid w-9/12 gap-y-5 mx-auto py-10">
                            <div class="text-left text-submitButton font-bold">No. of No Show</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <input type="number" min="1" name="numberOfNoShow" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black py-1">
                                <span class="mt-2 text-red-600 italic text-sm">@error('numberOfNoShow'){{ $message }}@enderror</span>
                            </div>

                            <div class="text-left text-submitButton font-bold">Type of Offense</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <select name="offenseTypeN" id="offenseTypeN" onchange="noShowBlockDays();" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black py-1">
                                    <option value="" selected="selected">--Choose Offense--</option>
                                    <option value="noOffense">No Offense</option>
                                    <option value="permanentBlock">Permanent Block</option>
                                    <option value="limitBlock">Limited Day/s of Block</option>
                                </select>
                                <span class="mt-2 text-red-600 italic text-sm">@error('offenseTypeN'){{ $message }}@enderror</span>
                                
                                <input type="number" min="1" placeholder="Number of Days Of Block (e.g. 2)" id="showNoShowBlockDays" style="display: none" name="noshowBlockDays" class="py-1 mt-2 w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                <span class="mt-2 text-red-600 italic text-sm">@error('noshowBlockDays'){{ $message }}@enderror</span>
                            </div>

                            
                            <div class="text-center mt-4 col-span-full">
                                <button id="btn-noShow-offense" type="submit" class="bg-submitButton text-white w-36 h-9 rounded-md font-Montserrat hover:bg-btnHoverColor transition duration-200 ease-in-out">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-span-1 bg-manageRestaurantSidebarColorActive pb-8 shadow-adminDownloadButton">
                <div class="w-11/12 mx-auto">
                    <div class="uppercase font-bold text-white text-xl py-3">No Show Status</div>
                </div>
                <div class="bg-adminViewAccountHeaderColor2 grid grid-cols-1 gap-y-2">
                    <div class="py-3"></div>
                    <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                        <p class="col-span-1 text-submitButton font-bold">No. of No Show</p>
                        <p class="col-span-1 place-self-end">{{ $noShow->noOfNoShows }}</p>
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


        <div class="grid grid-cols-2 gap-x-5 mt-16 items-start">
            <div class="col-span-1 bg-manageRestaurantSidebarColorActive pb-8 shadow-adminDownloadButton">
                <form action="/restaurant/manage-restaurant/offense/runaway/edit/{{ $runaway->id }}" id="runaway-offense-form"  method="POST">
                @csrf
                    <div class="w-11/12 mx-auto">
                        <div class="uppercase font-bold text-white text-xl py-3">Runaway Offense</div>
                    </div>
                    <div class="bg-white">
                        <div class="grid grid-cols-stampCardGrid w-9/12 gap-y-5 mx-auto py-10">
                            <div class="text-left text-submitButton font-bold">No. of Runaway</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <input type="number" min="1" name="numberOfRunaway" class="py-1 w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                <span class="mt-2 text-red-600 italic text-sm">@error('numberOfRunaway'){{ $message }}@enderror</span>
                            </div>


                            <div class="text-left text-submitButton font-bold">Type of Offense</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <select name="offenseTypeR" id="offenseTypeR" onchange="runawayBlockDay();" class="py-1 w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                    <option value="" selected="selected">--Choose Offense--</option>
                                    <option value="noOffense">No Offense</option>
                                    <option value="permanentBlock">Permanent Block</option>
                                    <option value="limitBlock">Limited Day/s of Block</option>
                                </select>
                                <span class="mt-2 text-red-600 italic text-sm">@error('offenseTypeR'){{ $message }}@enderror</span>
                                
                                <input type="number" min="1" placeholder="Number of Days Of Block (e.g. 2)" id="showRunawayBlockDays" style="display: none" name="runawayBlockDays" class="mt-2 py-1 w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                <span class="mt-2 text-red-600 italic text-sm">@error('runawayBlockDays'){{ $message }}@enderror</span>
                            </div>

                            
                            <div class="text-center mt-4 col-span-full">
                                <button id="btn-runaway-offense" type="submit" class="bg-submitButton text-white w-36 h-9 rounded-md font-Montserrat hover:bg-btnHoverColor transition duration-200 ease-in-out">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-span-1 bg-manageRestaurantSidebarColorActive pb-8 shadow-adminDownloadButton">
                <div class="w-11/12 mx-auto">
                    <div class="uppercase font-bold text-white text-xl py-3">Runaway Offense Status</div>
                </div>
                <div class="bg-adminViewAccountHeaderColor2 grid grid-cols-1 gap-y-2">
                    <div class="py-3"></div>
                    <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                        <p class="col-span-1 text-submitButton font-bold">No. of Runaway</p>
                        <p class="col-span-1 place-self-end">{{ $runaway->noOfRunaways }}</p>
                    </div>
                    <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                        <p class="col-span-1 text-submitButton font-bold">Offense Type</p>
                        <p class="col-span-1 place-self-end">
                            @if ($runaway->blockDays == "0")
                                No Offense
                            @elseif ($runaway->blockDays == "Permanent")
                                Permanent Block
                            @else
                                {{ $runaway->blockDays }} day/s of Block
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