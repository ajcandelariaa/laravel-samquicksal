@extends('restaurant.app3')

@section('content')
<div class="container mx-auto">
    <div class="w-11/12 mx-auto mt-10 pb-2">
        <a href="/restaurant/live-transaction/approved-customer/queue" class="text-submitButton uppercase font-bold">
            <i class="fas fa-chevron-left mr-2"></i>Back
        </a>
    </div>
    <div class="w-11/12 mx-auto mt-5 font-Montserrat bg-white pb-2">
        <form action="/restaurant/live-transaction/approved-customer/queue/add-walk-in" method="POST" id="addWalkInForm">
            @csrf
            <div class="bg-adminViewAccountHeaderColor2 grid grid-cols-2 items-center px-5 py-2">
                <div class="uppercase font-bold text-black text-xl py-3 col-span-1">CUSTOMER WALK-IN DETAILS</div>
                <div class="font-bold text-black col-span-1 text-right">
                    <button type="submit" class="text-white bg-postedStatus py-2 px-6">Submit</button>
                </div>
            </div>
            <div class="w-10/12 mx-auto py-10 font-Montserrat grid grid-cols-3 items-center gap-y-3">
                <div class="col-span-full">
                    <p class="italic text-gray-400">Note: {{ $rCapacityPerTable }} person/s only per table</p>
                    <input type="text" name="capacityPerTable" id="capacityPerTable" value="{{ $rCapacityPerTable }}" hidden>
                </div>

                <div class="col-span-1">Name: <span class="text-red-600">*</span></div>
                <div class="col-span-2 w-full">
                    <input type="text" name="walkInName" class="w-full py-2 px-3 border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('walkInName') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                    <span class="mt-2 text-red-600 italic text-sm">@error('walkInName'){{ $message }}@enderror</span>
                </div>
                
                <div class="col-span-1">Order: <span class="text-red-600">*</span></div>
                <div class="col-span-2 w-full">
                    <select name="walkInOrder" class="w-full py-2 px-3 border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('walkInOrder') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        @foreach ($orderSets as $orderSet)
                            <option value="{{ $orderSet->id }}">{{ $orderSet->orderSetName }}</option>
                        @endforeach
                    </select>
                    <span class="mt-2 text-red-600 italic text-sm">@error('walkInOrder'){{ $message }}@enderror</span>
                </div>
                
                <div class="col-span-1">No. of Persons: <span class="text-red-600">*</span></div>
                <div class="col-span-2 w-full">
                    <input type="number" min="2" name="walkInPersons" id="noOfPersons" class="w-full py-2 px-3 border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('walkInPersons') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                    <span class="mt-2 text-red-600 italic text-sm">@error('walkInPersons'){{ $message }}@enderror</span>
                </div>
                
                <div class="col-span-1">Hours of Stay: <span class="text-red-600">*</span></div>
                <div class="col-span-2 w-full">
                    @if ($rTimeLimit == 0)
                        <select name="walkInHoursOfStay" class="w-full py-2 px-3 border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('walkInHoursOfStay') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                            <option value="2">2 hours</option>
                            <option value="3">3 hours</option>
                            <option value="4">4 hours</option>
                            <option value="5">5 hours</option>
                        </select>
                        <span class="mt-2 text-red-600 italic text-sm">@error('walkInHoursOfStay'){{ $message }}@enderror</span>
                    @else
                        <input type="text" name="walkInHoursOfStay" class="w-full py-2 px-3 bg-gray-200 border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none focus:border-black" value="{{ $rTimeLimit }}" readonly>
                    @endif
                </div>
                
                <div class="col-span-1">No. of Tables: </div>
                <div class="col-span-2 w-full">
                    <input type="text" name="walkInNoOfTables" class="w-full py-2 px-3 bg-gray-200 border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none focus:border-black" value="0" id="noOfTables" disabled>
                </div>
                
                <div class="col-span-1">Senior Citizen/PWD: </div>
                <div class="col-span-2 w-full">
                    <input type="number" min="0" value="0" name="walkInPwd" class="w-full py-2 px-3 border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('walkInPwd') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                    <span class="mt-2 text-red-600 italic text-sm">@error('walkInPwd'){{ $message }}@enderror</span>
                </div>
                
                <div class="col-span-1">Children (7 below ): </div>
                <div class="col-span-2 w-full">
                    <input type="number" min="0" value="0" name="walkInChildren" class="w-full py-2 px-3 border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('walkInChildren') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                    <span class="mt-2 text-red-600 italic text-sm">@error('walkInChildren'){{ $message }}@enderror</span>
                </div>
                
                <div class="col-span-1 self-start">Notes: </div>
                <div class="col-span-2 w-full">
                    <textarea name="walkInNotes" rows="5" class="w-full py-2 px-3 border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none focus:border-black"></textarea>
                </div>

            </div>
        </form>
        
    </div>
</div>
@endsection