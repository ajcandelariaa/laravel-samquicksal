@extends('restaurant.app3')

@section('content')
<div class="container mx-auto">
    @if (session()->has('approved'))
        <script>
            Swal.fire(
                'Customer Approved',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('declined'))
        <script>
            Swal.fire(
                'Customer Declined',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10 font-Montserrat bg-adminViewAccountHeaderColor2 pb-2">
        <div class="bg-manageRestaurantSidebarColorActive">
            <div class="uppercase font-bold text-white text-center text-xl py-3">BOOK FOR QUEUE-IN</div>
        </div>
        
        <div class="grid grid-rows-1 gap-y-2">
            <div class="grid grid-cols-8 pt-4 pb-2 text-center">
                <div class="col-span-1">No.</div>
                <div class="col-span-2">Name</div>
                <div class="col-span-1">Persons</div>
                <div class="col-span-1">Priority Persons</div>
                <div class="col-span-2">Cancellation Time Left</div>
                <div class="col-span-1">View</div>
            </div>

           
            @if (!$customerQueues->isEmpty())
                @php
                    $count = 1
                @endphp
                @for ($i=0; $i<sizeOf($customerQueues); $i++)
                    @if ($count % 2 == 0)
                        <div class="grid grid-cols-8 py-2 text-center bg-manageFoodItemHeaderBgColor">
                            <div class="col-span-1">
                                @if (isset($_GET['page']))
                                    @if ($_GET['page'] == 1)
                                        {{ $count }}
                                    @else
                                        {{ ((($_GET['page'] - 1) * 9) + $count) }}
                                    @endif
                                @else
                                    {{ $count }}
                                @endif
                            </div>
                            <div class="col-span-2">{{ $customerNames[$i] }}</div>
                            <div class="col-span-1">{{ $customerQueues[$i]->numberOfPersons }}</div>
                            <div class="col-span-1">{{ $customerQueues[$i]->totalPwdChild }}</div>
                            <div class="col-span-2">{{ $customerTimeLimits[$i] }}</div>
                            <div class="col-span-1">
                                <a href="/restaurant/live-transaction/customer-booking/queue/{{ $customerQueues[$i]->id }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-8 py-2 text-center bg-white">
                            @if (isset($_GET['page']))
                                @if ($_GET['page'] == 1)
                                    {{ $count }}
                                @else
                                    {{ ((($_GET['page'] - 1) * 9) + $count) }}
                                @endif
                            @else
                                {{ $count }}
                            @endif
                            <div class="col-span-2">{{ $customerNames[$i] }}</div>
                            <div class="col-span-1">{{ $customerQueues[$i]->numberOfPersons }}</div>
                            <div class="col-span-1">{{ $customerQueues[$i]->totalPwdChild }}</div>
                            <div class="col-span-2">{{ $customerTimeLimits[$i] }}</div>
                            <div class="col-span-1">
                                <a href="/restaurant/live-transaction/customer-booking/queue/{{ $customerQueues[$i]->id }}">
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

    @if (!$customerQueues->isEmpty())
        <div class="mx-auto w-11/12 mt-6">
            {{ $customerQueues->links() }}
        </div>
    @endif
    
    @if ($customerQueues->isEmpty())
        <div class="mx-auto w-11/12 mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 shadow-xl rounded-lg">
            <div class="text-center text-multiStepBoxColor uppercase">There are no pending customer queues as of now</div>
        </div>
    @endif
</div>
@endsection