@extends('restaurant.app2')

@section('content')
<div class="container mx-auto stamp-card">
    <div class="w-11/12 mx-auto my-10">
        <div class="grid grid-cols-3 gap-x-5 items-start">
            <div class="col-span-2 bg-manageRestaurantSidebarColorActive pb-8 shadow-adminDownloadButton">
                <div class="w-11/12 mx-auto">
                    <div class="uppercase font-bold text-white text-xl py-3">Manage stamp card</div>
                </div>
                <div class="bg-white">
                    <div class="w-11/12 mx-auto pt-7">
                        <div class="text-manageRestaurantSidebarColor text-sm italic">Once published, you can no longer edit this section unless the reward has completed its validity period. </div>
                    </div>
                    <form action="/restaurant/manage-restaurant/task-rewards/stamp-card/add" method="POST">
                        @csrf
                        <div class="grid grid-cols-stampCardGrid w-9/12 gap-y-5 mx-auto py-10">
                            <div class="text-left text-submitButton font-bold">Stamp Capacity</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <input type="text" name="stampCapacity" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                <span class="mt-2 text-red-600 italic text-sm">@error('stampCapacity'){{ $message }}@enderror</span>
                            </div>
    
                            <div class="text-left text-submitButton font-bold">Reward</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <select name="stampRewards" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                    <option value="" selected="selected">--Choose Reward--</option>
                                    <option value="DSCN">Discount</option>
                                    <option value="FRPE">Free Person</option>
                                    <option value="HLF">Free Half</option>
                                    <option value="ALL">All are Free</option>
                                </select>
                                <span class="mt-2 text-red-600 italic text-sm">@error('stampRewards'){{ $message }}@enderror</span>
                            </div>
    
                            <div class="text-left text-submitButton font-bold">Tasks</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="grid grid-rows-1">
                                <div>
                                    <input type="checkbox" name="taks[]" value="SPND" class="mr-2 cursor-pointer"> Spend Amount Of Money
                                </div>
                                <div>
                                    <input type="checkbox" name="taks[]" value="BRNG" class="mr-2 cursor-pointer"> Bring Friends
                                </div>
                                <div>
                                    <input type="checkbox" name="taks[]" value="ORDR" class="mr-2 cursor-pointer"> Order Add On/s
                                </div>
                                <div>
                                    <input type="checkbox" name="taks[]" value="VST" class="mr-2 cursor-pointer"> Visit in Store
                                </div>
                                <div>
                                    <input type="checkbox" name="taks[]" value="FDBK" class="mr-2 cursor-pointer"> Give Feedback
                                </div>
                                <div>
                                    <input type="checkbox" name="taks[]" value="PUGC" class="mr-2 cursor-pointer"> Pay using GCash
                                </div>
                                <div>
                                    <input type="checkbox" name="taks[]" value="LUDN" class="mr-2 cursor-pointer"> Eat during lunch/dinner hours
                                </div>
                                <span class="mt-2 text-red-600 italic text-sm">@error('taks'){{ $message }}@enderror</span>
                            </div>
    
                            <div class="text-left text-submitButton font-bold">Validity Period</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="">
                                <input type="date" name="stampValidity" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                <span class="mt-2 text-red-600 italic text-sm">@error('stampValidity'){{ $message }}@enderror</span>
                            </div>
    
                            <div class="text-center mt-4 col-span-full">
                                <button type="submit" class="bg-submitButton text-white w-36 h-9 rounded-md">Publish</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-span-1 bg-manageRestaurantSidebarColorActive pb-8 shadow-adminDownloadButton">
                <div class="w-11/12 mx-auto">
                    <div class="uppercase font-bold text-white text-xl py-3">Current Status</div>
                </div>
                <div class="bg-adminViewAccountHeaderColor2 grid grid-cols-1 gap-y-2">
                    <div></div>
                    @if ($stamp == null)
                        <div class="grid grid-cols-1 w-full items-center bg-white py-5">
                            <p class="text-center text-submitButton font-bold">You don't have any stamp card right now</p>
                        </div>
                        <div></div>
                    @else
                        <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                            <p class="col-span-1 text-submitButton font-bold">Stamp Capacity</p>
                            <p class="col-span-1 place-self-end">10</p>
                        </div>
                        <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                            <p class="col-span-1 text-submitButton font-bold">Reward</p>
                            <p class="col-span-1 place-self-end">Discount</p>
                            <p class="col-span-full mt-5 ml-5 text-sm">Customer will receive 20% discount once stamp card has been completed.</p>
                        </div>
                        <div class="bg-white pl-5 pr-11 py-5">
                            <p class="text-submitButton font-bold">Tasks</p>
                            <ul class="mt-2">
                                <li>Visit The Restaurant</li>
                                <li>Visit The Restaurant</li>
                                <li>Visit The Restaurant</li>
                                <li>Visit The Restaurant</li>
                                <li>Visit The Restaurant</li>
                                <li>Visit The Restaurant</li>
                                <li>Visit The Restaurant</li>
                            </ul>
                        </div>
                        <div></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection