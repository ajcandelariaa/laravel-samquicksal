@extends('restaurant.app2')

@section('content')
<div class="container mx-auto store-hours">
    @if (session()->has('added'))
        <script>
            Swal.fire(
                'Store Hour Added',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('edited'))
        <script>
            Swal.fire(
                'Store Hour Updated',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('emptyDays'))
        <script>
            Swal.fire(
                'Please select at least one day',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('cantDelete'))
        <script>
            Swal.fire(
                'You can\'t delete this one',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('openingTimeExceed'))
        <script>
            Swal.fire(
                'Opening Time should not between 12am to 5am',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('closingTimeExceed'))
        <script>
            Swal.fire(
                'Closing Time should not exceed than 4am',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('closingTimeExceed2'))
        <script>
            Swal.fire(
                'Store should open more than 3 hours',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('deleted'))
        <script>
            Swal.fire(
                'Store Hour Deleted',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('mustNotTheSame'))
        <script>
            Swal.fire(
                'Opening Time and Closing Time must be different',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('stillOpenHours'))
        <script>
            Swal.fire(
                'You are currently Open',
                'Wait till your store is closed',
                'error'
            );
        </script>
    @endif
    
    <div class="w-11/12 mx-auto mt-10 font-Montserrat">
        <div class="w-full mt-3 rounded-2xl shadow-adminDownloadButton items-center bg-manageRestaurantSidebarColorActive pb-7">
            <div class="flex justify-between rounded-t-2xl px-5 py-4 items-center">
                <div class="text-white font-bold uppercase text-xl">Manage Store hours</div>
                <button id="btn-add-item" class="bg-submitButton text-white w-36 h-9 rounded-md font-Montserrat hover:bg-darkerSubmitButton transition duration-200 ease-in-out "><i class="fas fa-plus mr-3"></i>Time Slot</button>
            </div>
            <div class="bg-white py-5">
                <div class="w-10/12 mx-auto grid grid-cols-3 gap-5 mb-3">
                    @foreach ($storeHours as $storeHour)
                        @php
                            $days = explode(",", $storeHour->days)
                        @endphp
                        <div class="tr col-span-1 shadow-adminDownloadButton rounded-lg p-2">
                            <div hidden class="td">{{ $storeHour->openingTime }}</div>
                            <div hidden class="td">{{ $storeHour->closingTime }}</div>
                            <div hidden class="td">{{ $storeHour->id }}</div>
                            <div hidden class="col-span-1 {{ (in_array('SU', $days)) ? 'text-submitButton td' : 'text-white' }}">SU</div>
                            <div hidden class="col-span-1 {{ (in_array('MO', $days)) ? 'text-submitButton td' : 'text-white' }}">MO</div>
                            <div hidden class="col-span-1 {{ (in_array('TU', $days)) ? 'text-submitButton td' : 'text-white' }}">TU</div>
                            <div hidden class="col-span-1 {{ (in_array('WE', $days)) ? 'text-submitButton td' : 'text-white' }}">WE</div>
                            <div hidden class="col-span-1 {{ (in_array('TH', $days)) ? 'text-submitButton td' : 'text-white' }}">TH</div>
                            <div hidden class="col-span-1 {{ (in_array('FR', $days)) ? 'text-submitButton td' : 'text-white' }}">FR</div>
                            <div hidden class="col-span-1 {{ (in_array('SA', $days)) ? 'text-submitButton td' : 'text-white' }}">SA</div>

                            <div class="bg-adminViewAccountHeaderColor2 p-4 rounded-lg">
                                <div class="grid grid-cols-7 rounded-full bg-sundayToSaturdayBoxColor p-1 text-xs text-center">
                                    <div class="col-span-1 {{ (in_array('SU', $days)) ? 'text-submitButton' : 'text-white' }}">SU</div>
                                    <div class="col-span-1 {{ (in_array('MO', $days)) ? 'text-submitButton' : 'text-white' }}">MO</div>
                                    <div class="col-span-1 {{ (in_array('TU', $days)) ? 'text-submitButton' : 'text-white' }}">TU</div>
                                    <div class="col-span-1 {{ (in_array('WE', $days)) ? 'text-submitButton' : 'text-white' }}">WE</div>
                                    <div class="col-span-1 {{ (in_array('TH', $days)) ? 'text-submitButton' : 'text-white' }}">TH</div>
                                    <div class="col-span-1 {{ (in_array('FR', $days)) ? 'text-submitButton' : 'text-white' }}">FR</div>
                                    <div class="col-span-1 {{ (in_array('SA', $days)) ? 'text-submitButton' : 'text-white' }}">SA</div>
                                </div>
                            </div>
                            <div class="mt-3 grid grid-cols-3 text-xs text-center items-center w-9/12 mx-auto">
                                <div class="text-submitButton">Opening</div>
                                <div class="text-submitButton">:</div>
                                <div>{{ $storeHour->openingTime }}</div>
                            </div>
                            <div class="tr mt-3 grid grid-cols-3 text-xs text-center items-center w-9/12 mx-auto">
                                <div class="text-submitButton">Closing</div>
                                <div class="text-submitButton">:</div>
                                <div>{{ $storeHour->closingTime }}</div>
                            </div>
                            @if ($storeHour->type == "Permanent")
                            <div class="mt-4 mb-1 text-right">
                                <button class="btn-edit-item text-white bg-manageRestaurantSidebarColorActive hover:text-gray-300 hover:bg-hoverManageRestaurantSidebarColorActive inline-block text-center leading-6 w-6 h-6 rounded-full text-xs transition duration-200 ease-in-out "><i class="fas fa-edit"></i></button>
                            </div>
                            @else
                            <div class="mt-4 mb-1 text-right">
                                <button class="btn-edit-item text-white bg-manageRestaurantSidebarColorActive hover:text-gray-300 hover:bg-hoverManageRestaurantSidebarColorActive inline-block text-center leading-6 w-6 h-6 rounded-full text-xs transition duration-200 ease-in-out"><i class="fas fa-edit"></i></button>
                                <a href="/restaurant/manage-restaurant/time/store-hours/delete/{{ $storeHour->id }}"  class="btn-delete text-white bg-submitButton inline-block text-center leading-6 w-6 h-6 rounded-full text-xs transition duration-200 ease-in-out hover:bg-darkerSubmitButton hover:text-gray-300"><i class="fas fa-trash-alt"></i></a>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="create-form" id="create-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl">Add a Time Slot</h1>
            <form action="/restaurant/manage-restaurant/time/store-hours/add" method="POST" id="storeHour-add-form">
                @csrf
                <div class="grid grid-cols-2 w-9/12 mx-auto text-center items-center mt-5">
                    <div class="grid grid-cols-storeHoursThreeCols">
                        <div>Opening</div>
                        <div>:</div>
                        <div>
                            <input type="time" name="openingTime" class="border focus:border-black w-9/12 py-1 px-2 text-sm focus:outline-non text-gray-700" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-storeHoursThreeCols">
                        <div>Closing</div>
                        <div>:</div>
                        <div><input type="time" name="closingTime" class="border focus:border-black w-9/12 py-1 px-2 text-sm focus:outline-non text-gray-700" required></div>
                    </div>
                </div>
                <div class="mt-5 bg-multiStepBoxColor pt-2 pb-5 w-9/12 mx-auto">
                    <div class="text-center">Days Open</div>
                    <div class="mt-5 grid grid-cols-4 gap-y-2 items-center w-addFoodItemModalW mx-auto">
                        <div class="col-span-1"><input type="checkbox" name="days[]" value="SU" {{ (in_array("SU", $existingDays)) ? 'disabled' : '' }}> Sunday</div>
                        <div class="col-span-1"><input type="checkbox" name="days[]" value="MO" {{ (in_array("MO", $existingDays)) ? 'disabled' : '' }}> Monday</div>
                        <div class="col-span-1"><input type="checkbox" name="days[]" value="TU" {{ (in_array("TU", $existingDays)) ? 'disabled' : '' }}> Tuesday</div>
                        <div class="col-span-1"><input type="checkbox" name="days[]" value="WE" {{ (in_array("WE", $existingDays)) ? 'disabled' : '' }}> Wednesday</div>
                        <div class="col-span-1"><input type="checkbox" name="days[]" value="TH" {{ (in_array("TH", $existingDays)) ? 'disabled' : '' }}> Thursday</div>
                        <div class="col-span-1"><input type="checkbox" name="days[]" value="FR" {{ (in_array("FR", $existingDays)) ? 'disabled' : '' }}> Friday</div>
                        <div class="col-span-1"><input type="checkbox" name="days[]" value="SA" {{ (in_array("SA", $existingDays)) ? 'disabled' : '' }}> Saturday</div>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button id="btn-storeHour-add" class="bg-submitButton text-white hover:bg-darkerSubmitButton hover:text-gray-300 rounded-md rounded-w-9/12 w-32 h-10 text-sm transition duration-200 ease-in-out uppercase font-bold" type="submit">Add</button>
                </div>
            </form>
        </div> 


        <div class="edit-form font-Montserrat" id="edit-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl">Edit Time Slot</h1>
            <form action="/restaurant/manage-restaurant/time/store-hours/edit" method="POST" id="storeHour-edit-form">
                @csrf
                <input type="text" name="storeId" id="storeId" hidden>
                <div class="grid grid-cols-2 w-9/12 mx-auto text-center items-center mt-5">
                    <div class="grid grid-cols-storeHoursThreeCols">
                        <div>Opening</div>
                        <div>:</div>
                        <div>
                            <input type="time" id="updateOpeningTime" name="updateOpeningTime" class="border focus:border-black w-9/12 py-1 px-2 text-sm focus:outline-non text-gray-700" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-storeHoursThreeCols">
                        <div>Closing</div>
                        <div>:</div>
                        <div><input type="time" id="updateClosingTime" name="updateClosingTime" class="border focus:border-black w-9/12 py-1 px-2 text-sm focus:outline-non text-gray-700" required></div>
                    </div>
                </div>
                <div class="mt-5 bg-multiStepBoxColor pt-2 pb-5 w-9/12 mx-auto">
                    <div class="text-center">Days Open</div>
                    <div class="mt-5 grid grid-cols-4 gap-y-2 items-center w-addFoodItemModalW mx-auto">
                        <div class="col-span-1"><input type="checkbox" id="SU" name="updateDays[]" value="SU" {{ (in_array("SU", $existingDays)) ? 'disabled' : '' }}> Sunday</div>
                        <div class="col-span-1"><input type="checkbox" id="MO" name="updateDays[]" value="MO" {{ (in_array("MO", $existingDays)) ? 'disabled' : '' }}> Monday</div>
                        <div class="col-span-1"><input type="checkbox" id="TU" name="updateDays[]" value="TU" {{ (in_array("TU", $existingDays)) ? 'disabled' : '' }}> Tuesday</div>
                        <div class="col-span-1"><input type="checkbox" id="WE" name="updateDays[]" value="WE" {{ (in_array("WE", $existingDays)) ? 'disabled' : '' }}> Wednesday</div>
                        <div class="col-span-1"><input type="checkbox" id="TH" name="updateDays[]" value="TH" {{ (in_array("TH", $existingDays)) ? 'disabled' : '' }}> Thursday</div>
                        <div class="col-span-1"><input type="checkbox" id="FR" name="updateDays[]" value="FR" {{ (in_array("FR", $existingDays)) ? 'disabled' : '' }}> Friday</div>
                        <div class="col-span-1"><input type="checkbox" id="SA" name="updateDays[]" value="SA" {{ (in_array("SA", $existingDays)) ? 'disabled' : '' }}> Saturday</div>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button id="btn-storeHour-edit" class="bg-submitButton hover:bg-darkerSubmitButton text-white hover:text-gray-300 rounded-md rounded-w-9/12 w-32 h-10 text-sm uppercase font-bold transition duration-200 ease-in-out" type="submit">UPDATE</button>
                </div>
            </form>
        </div> 
        <div class="overlay"></div>
    </div>
</div>
@endsection