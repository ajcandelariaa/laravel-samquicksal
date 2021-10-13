@extends('restaurant.app2')

@section('content')
<form action="/restaurant/manage-restaurant/time/time-limit/edit" method="POST">
<div class="container mx-auto store-hours">
    <div class="w-11/12 mx-auto mt-10">
        <div class="w-full mt-3 rounded-2xl shadow-adminDownloadButton items-center bg-manageRestaurantSidebarColorActive pb-7">
            <div class="flex justify-between rounded-t-2xl px-5 py-4 items-center">
                <div class="text-white font-bold uppercase text-xl font-Montserrat">Dine in Time limit</div>
                <button id="btn-add-item" class="bg-submitButton hover:bg-darkerSubmitButton text-white hover:text-gray-300 w-36 h-9 rounded-md uppercase font-bold transition duration-200 ease-in-out">Save</button>
            </div>
            <div class="bg-white py-32">
                <div class="w-full mx-auto text-center uppercase text-5xl">
                        @csrf
                        <select name="timeLimit">
                            @foreach ($optionsArray as $options)
                                @if ($options == $account->rTimeLimit)
                                    <option selected='selected' value="{{ $options }}">{{ ($options == 0) ? 'No  time limit' : $options.' hours' }}</option>
                                @else
                                    <option value="{{ $options }}">{{ ($options == 0) ? 'No  time limit' : $options.' hours' }}</option>
                                @endif
                            @endforeach
                        </select>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection