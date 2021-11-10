@extends('admin.app')

@section('content')
<div class="bg-gray-200 w-full">
    <div class="container mx-auto">
        <div class="mx-auto w-11/12 mt-10">
            <form action="/admin/restaurant-accounts" method="GET">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-gray-400">
                      <button type="submit" class="p-1 focus:outline-none focus:shadow-outline">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24" class="w-6 h-6"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                      </button>
                    </span>
                    <input type="text" name="q" class="w-80 py-2 text-sm text-white bg-white rounded-md pl-10 focus:outline-none focus:text-gray-900" placeholder="Search..." autocomplete="off">
                </div>
            </form>
        </div>
        <div class="mx-auto w-11/12 mt-6 bg-tableBgHeader shadow-xl">
            <div class="grid grid-cols-12 items-center text-center font-bold h-16 px-5">
                <div class="col-span-1">No.</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-3">Restaurant Name</div>
                <div class="col-span-3">Restaurant Branch</div>
                <div class="col-span-1">State</div>
                <div class="col-span-1">City</div>
                <div class="col-span-1">Country</div>
                <div class="col-span-1">View</div>
            </div>
        @if (!$accounts->isEmpty())
                @php
                    $count = 1
                @endphp
                @foreach ($accounts as $account)
                    @if ($count % 2 == 0)
                        <div class="bg-white grid grid-cols-12 items-center text-center h-10 px-5 mb-3 text-sm">
                            <div class="col-span-1">{{ $count }}</div>
                            <div class="col-span-1">{{ $account->status }}</div>
                            <div class="col-span-3">{{ $account->rName }}</div>
                            <div class="col-span-3">{{ $account->rBranch }}</div>
                            <div class="col-span-1">{{ $account->rState }}</div>
                            <div class="col-span-1">{{ $account->rCity }}</div>
                            <div class="col-span-1">{{ $account->rCountry }}</div>
                            <div class="col-span-1">
                                <a href="/admin/restaurant-accounts/{{ $account->id }}"><i class="fas fa-eye"></i></a>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-100 grid grid-cols-12 items-center text-center h-10 px-5 mb-3 text-sm">
                            <div class="col-span-1">{{ $count }}</div>
                            <div class="col-span-1">{{ $account->status }}</div>
                            <div class="col-span-3">{{ $account->rName }}</div>
                            <div class="col-span-3">{{ $account->rBranch }}</div>
                            <div class="col-span-1">{{ $account->rState }}</div>
                            <div class="col-span-1">{{ $account->rCity }}</div>
                            <div class="col-span-1">{{ $account->rCountry }}</div>
                            <div class="col-span-1">
                                <a href="/admin/restaurant-accounts/{{ $account->id }}"><i class="fas fa-eye"></i></a>
                            </div>
                        </div>
                    @endif
                    @php
                        $count++
                    @endphp
                @endforeach
            @endif
        </div>

        @if ($accounts->isEmpty())
            <div class="mx-auto w-11/12 mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 shadow-xl rounded-lg">
                <div class="text-center text-multiStepBoxColor uppercase">There are no restaurant accounts right now</div>
            </div>
        @endif

        <div class="mx-auto w-11/12 mt-6">
            {{ $accounts->links() }}
        </div>
        
    </div>
</div>
@endsection