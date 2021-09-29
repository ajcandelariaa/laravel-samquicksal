@extends('restaurant.app2')

@section('content')
<div class="container mx-auto store-hours">
    @if (session()->has('edited'))
        <script>
            Swal.fire(
                'Time Limit Edited',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto my-10">
        <div class="w-full mt-3 shadow-adminDownloadButton items-center bg-manageRestaurantSidebarColorActive pb-10">
            <div class="flex justify-between px-5 py-4 items-center">
                <div class="text-white font-bold uppercase text-xl">Publish Restaurant</div>
                <a href="/restaurant/manage-restaurant/time/time-limit/edit" class="bg-submitButton text-white w-36 h-9 leading-9 text-center rounded-md">Publish</a>
            </div>
            <div class="bg-white">
                <div class="w-11/12 mx-auto grid grid-cols-checkListGrid py-5">
                    <div>
                        <div class=""><span><i class="fas fa-circle text-completeColor mr-2"></i></span> Incomplete</div>
                        <div class=""><span><i class="fas fa-circle text-postedStatus mr-2 mt-2"></i></span> Complete</div>
                    </div>
                    <div class="grid grid-cols-checkListGrid2 font-bold">
                        <div class="grid grid-rows-6 gap-y-8 my-10 relative">
                            <div class="mr-10 grid items-center text-white bg-completeColor w-12 h-12 text-center rounded-full z-20">1</div>
                            <div class="mr-10 grid items-center text-white bg-completeColor w-12 h-12 text-center rounded-full z-20">2</div>
                            <div class="mr-10 grid items-center text-white bg-postedStatus w-12 h-12 text-center rounded-full z-20">3</div>
                            <div class="mr-10 grid items-center text-white bg-postedStatus w-12 h-12 text-center rounded-full z-20">4</div>
                            <div class="mr-10 grid items-center text-white bg-postedStatus w-12 h-12 text-center rounded-full z-20">5</div>
                            <div class="mr-10 grid items-center text-white bg-completeColor w-12 h-12 text-center rounded-full z-20">6</div>
                            <div class="border-multiStepBoxBorder bg-multiStepBoxColor absolute h-full top-0 left-1/3 z-10"></div>
                        </div>
                        <div class="grid grid-rows-6 gap-y-8 my-10 items-center">
                            <div class="cursor-pointer hover:underline" id="clickAboutRestaurant">About Restaurant <i class="far fa-question-circle"></i></div>
                            <div class="cursor-pointer hover:underline" id="clickFoodMenu">Food Menu <i class="far fa-question-circle"></i></div>
                            <div class="cursor-pointer hover:underline" id="clickTaskRewards">Tasks & Rewards <i class="far fa-question-circle"></i></div>
                            <div class="cursor-pointer hover:underline" id="clickPromos">Promos <i class="far fa-question-circle"></i></div>
                            <div class="cursor-pointer hover:underline" id="clickManageTime">Manage Time <i class="far fa-question-circle"></i></div>
                            <div class="cursor-pointer hover:underline" id="clickManageOffense">Manage Offense <i class="far fa-question-circle"></i></div>
                        </div>
                    </div>
                    

                    {{-- MORE INFO --}}
                    <div>
                        <div class="bg-manageRestaurantSidebarColorActive my-10 rounded-md pb-7" id="aboutRestaurant">
                            <div class="text-center font-bold text-white py-4">About Restaurant</div>
                            <div class="grid grid-cols-2 gap-x-1 w-11/12 mx-auto">
                                <div class="col-span-1 bg-white">
                                    <div class="bg-completeColor text-center font-bold py-1">Incomplete</div>
                                    <div class="w-7/12 mx-auto my-5">
                                        <ul class="list-disc">
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-span-1 bg-white">
                                    <div class="bg-postedStatus text-center font-bold py-1">Complete</div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-manageRestaurantSidebarColorActive my-10 rounded-md pb-7" id="foodMenu" hidden>
                            <div class="text-center font-bold text-white py-4">Food Menu</div>
                            <div class="grid grid-cols-2 gap-x-1 w-11/12 mx-auto">
                                <div class="col-span-1 bg-white">
                                    <div class="bg-completeColor text-center font-bold py-1">Incomplete</div>
                                    <div class="w-7/12 mx-auto my-5">
                                        <ul class="list-disc">
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-span-1 bg-white">
                                    <div class="bg-postedStatus text-center font-bold py-1">Complete</div>
                                    <div class="w-7/12 mx-auto my-5">
                                        <ul class="list-disc">
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-manageRestaurantSidebarColorActive my-10 rounded-md pb-7" id="taskRewards" hidden>
                            <div class="text-center font-bold text-white py-4">Task & Rewards</div>
                            <div class="grid grid-cols-2 gap-x-1 w-11/12 mx-auto">
                                <div class="col-span-1 bg-white">
                                    <div class="bg-completeColor text-center font-bold py-1">Incomplete</div>
                                </div>
                                <div class="col-span-1 bg-white">
                                    <div class="bg-postedStatus text-center font-bold py-1">Complete</div>
                                    <div class="w-7/12 mx-auto my-5">
                                        <ul class="list-disc">
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-manageRestaurantSidebarColorActive my-10 rounded-md pb-7" id="promos" hidden>
                            <div class="text-center font-bold text-white py-4">Promos</div>
                            <div class="grid grid-cols-2 gap-x-1 w-11/12 mx-auto">
                                <div class="col-span-1 bg-white">
                                    <div class="bg-completeColor text-center font-bold py-1">Incomplete</div>
                                    <div class="w-7/12 mx-auto my-5">
                                        <ul class="list-disc">
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-span-1 bg-white">
                                    <div class="bg-postedStatus text-center font-bold py-1">Complete</div>
                                    <div class="w-7/12 mx-auto my-5">
                                        <ul class="list-disc">
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-manageRestaurantSidebarColorActive my-10 rounded-md pb-7" id="time" hidden>
                            <div class="text-center font-bold text-white py-4">Manage Time</div>
                            <div class="grid grid-cols-2 gap-x-1 w-11/12 mx-auto">
                                <div class="col-span-1 bg-white">
                                    <div class="bg-completeColor text-center font-bold py-1">Incomplete</div>
                                </div>
                                <div class="col-span-1 bg-white">
                                    <div class="bg-postedStatus text-center font-bold py-1">Complete</div>
                                    <div class="w-7/12 mx-auto my-5">
                                        <ul class="list-disc">
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-manageRestaurantSidebarColorActive my-10 rounded-md pb-7" id="offense" hidden>
                            <div class="text-center font-bold text-white py-4">Manage Offense</div>
                            <div class="grid grid-cols-2 gap-x-1 w-11/12 mx-auto">
                                <div class="col-span-1 bg-white">
                                    <div class="bg-completeColor text-center font-bold py-1">Incomplete</div>
                                    <div class="w-7/12 mx-auto my-5">
                                        <ul class="list-disc">
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-span-1 bg-white">
                                    <div class="bg-postedStatus text-center font-bold py-1">Complete</div>
                                    <div class="w-7/12 mx-auto my-5">
                                        <ul class="list-disc">
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                            <li class="-mt-2">a</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection