@extends('restaurant.app2')

@section('content')
<div class="container mx-auto stamp-card">
    @if (session()->has('added'))
        <script>
            Swal.fire(
                'Stamp Card Published',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('invalid'))
        <script>
            Swal.fire(
                'You have still stamp card',
                '',
                'error'
            );
        </script>
    @endif
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
                    <form action="/restaurant/manage-restaurant/task-rewards/stamp-card/add" method="POST" id="stampCardForm">
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
                                <select name="stampReward" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  focus:border-black">
                                    <option value="" selected="selected">--Choose Reward--</option>
                                    <option value="{{ $rewards[0]->id }}">Discount {{ $rewards[0]->rewardInput }}% in a Total Bill</option>
                                    <option value="{{ $rewards[1]->id }}">Free {{ $rewards[1]->rewardInput }} person in a group</option>
                                    <option value="{{ $rewards[2]->id }}">Half in the group will be free</option>
                                    <option value="{{ $rewards[3]->id }}">All people in the group will be free</option>
                                </select>
                                <span class="mt-2 text-red-600 italic text-sm">@error('stampRewards'){{ $message }}@enderror</span>
                            </div>
    
                            <div class="text-left text-submitButton font-bold">Tasks</div>
                            <div class="text-left text-submitButton">:</div>
                            <div class="grid grid-rows-1">
                                <div>
                                    <input type="checkbox" name="tasks[]" value="{{ $tasks[0]->id }}" class="mr-2 cursor-pointer"> Spend {{ $tasks[0]->taskInput }} pesos in 1 visit only
                                </div>
                                <div>
                                    <input type="checkbox" name="tasks[]" value="{{ $tasks[1]->id }}" class="mr-2 cursor-pointer"> Bring {{ $tasks[1]->taskInput }} friends in our store
                                </div>
                                <div>
                                    <input type="checkbox" name="tasks[]" value="{{ $tasks[2]->id }}" class="mr-2 cursor-pointer"> Order {{ $tasks[2]->taskInput }} add on/s per visit
                                </div>
                                <div>
                                    <input type="checkbox" name="tasks[]" value="{{ $tasks[3]->id }}" class="mr-2 cursor-pointer"> Visit in our store
                                </div>
                                <div>
                                    <input type="checkbox" name="tasks[]" value="{{ $tasks[4]->id }}" class="mr-2 cursor-pointer"> Give a feedback/review per visit
                                </div>
                                <div>
                                    <input type="checkbox" name="tasks[]" value="{{ $tasks[5]->id }}" class="mr-2 cursor-pointer"> Pay using gcash
                                </div>
                                <div>
                                    <input type="checkbox" name="tasks[]" value="{{ $tasks[6]->id }}" class="mr-2 cursor-pointer"> Eat during lunch/dinner hours
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
                            <p class="col-span-1 place-self-end">{{ $stamp->stampCapacity }}</p>
                        </div>
                        <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                            <p class="col-span-1 text-submitButton font-bold">Stamp Validity Date</p>
                            <p class="col-span-1 place-self-end">
                                @php
                                    $stampDate = explode('-', $stamp->stampValidity);
                                    switch ($stampDate[1]) {
                                        case '1':
                                            $month = "January";
                                            break;
                                        case '2':
                                            $month = "February";
                                            break;
                                        case '3':
                                            $month = "March";
                                            break;
                                        case '4':
                                            $month = "April";
                                            break;
                                        case '5':
                                            $month = "May";
                                            break;
                                        case '6':
                                            $month = "June";
                                            break;
                                        case '7':
                                            $month = "July";
                                            break;
                                        case '8':
                                            $month = "August";
                                            break;
                                        case '9':
                                            $month = "September";
                                            break;
                                        case '10':
                                            $month = "October";
                                            break;
                                        case '11':
                                            $month = "November";
                                            break;
                                        case '12':
                                            $month = "December";
                                            break;
                                        default:
                                            $month = "";
                                            break;
                                    }
                                    $year = $stampDate[0];
                                    $day  = $stampDate[2];
                                @endphp
                                {{ $month.' '.$day.', '.$year }}
                            </p>
                        </div>
                        <div class="grid grid-cols-2 w-full items-center bg-white pl-5 pr-11 py-5">
                            <p class="col-span-1 text-submitButton font-bold">Reward</p>
                            <p class="col-span-full mt-5 ml-5 text-sm">
                                @switch($reward->rewardCode)
                                    @case('DSCN')
                                        Discount {{ $reward->rewardInput }}% in a Total Bill
                                        @break
                                    @case('FRPE')
                                        Free {{ $reward->rewardInput }} person in a group
                                        @break
                                    @case('HLF')
                                        Half in the group will be free
                                        @break
                                    @case('ALL')
                                        All people in the group will be free
                                        @break
                                    @default
                                        No Reward
                                @endswitch
                            </p>
                        </div>
                        <div class="bg-white pl-5 pr-11 py-5">
                            <p class="text-submitButton font-bold">Tasks</p>
                            <ul class="mt-2 list-disc pl-10 text-sm">
                                @foreach ($storeTasksId as $storeTaskId)
                                    @foreach ($tasks as $task)
                                        @if ($storeTaskId == $task->id)
                                            @switch($task->taskCode)
                                                @case('SPND')
                                                    <li>Spend {{ $task->taskInput }} pesos in 1 visit only</li>
                                                    @break
                                                @case('BRNG')
                                                    <li>Bring {{ $task->taskInput }} friends in our store</li>
                                                    @break
                                                @case('ORDR')
                                                    <li>Order {{ $task->taskInput }} add on/s per visit</li>
                                                    @break
                                                @case('VST')
                                                    <li>Visit in our store</li>
                                                    @break
                                                @case('FDBK')
                                                    <li>Give a feedback/review per visit
                                                    </li>
                                                    @break
                                                @case('PUGC')
                                                    <li>Pay using gcash</li>
                                                    @break
                                                @case('LUDN')
                                                    <li>Eat during lunch/dinner hours</li>
                                                    @break
                                                @default
                                                    <li>No Task</li>
                                            @endswitch
                                        @endif
                                    @endforeach
                                @endforeach
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