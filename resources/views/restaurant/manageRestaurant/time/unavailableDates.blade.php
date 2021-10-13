@extends('restaurant.app2')

@section('content')
<div class="container mx-auto store-hours">
    @if (session()->has('existing'))
        <script>
            Swal.fire(
                'Date are already existed',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('added'))
        <script>
            Swal.fire(
                'Date Added',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('deleted'))
        <script>
            Swal.fire(
                'Date Deleted',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('edited'))
        <script>
            Swal.fire(
                'Date Updated',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10">
        <div class="mt-5 text-right">
            <button id="btn-add-item" class="bg-submitButton text-white w-36 h-9 rounded-md font-Montserrat hover:bg-darkerSubmitButton transition duration-200 ease-in-out"><i class="fas fa-plus mr-3"></i>Date</button>
        </div>
        <div class="grid grid-cols-2 gap-5 mt-5 w-11/12 mx-auto items-center">
            @foreach ($unavailableDates as $unavailableDate)
                @php
                    $orderdate = explode('-', $unavailableDate->unavailableDatesDate);
                    switch ($orderdate[1]) {
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
                    $year = $orderdate[0];
                    $day  = $orderdate[2];
                @endphp
                <div class="tr col-span-1 grid grid-cols-unavailableDatesGrid bg-white shadow-adminDownloadButton rounded-xl px-3 py-5 gap-5">
                    <p class="td" hidden>{{ $unavailableDate->id }}</p>
                    <p class="td" hidden>{{ $unavailableDate->unavailableDatesDate }}</p>
                    <p class="td" hidden>{{ $unavailableDate->unavailableDatesDesc }}</p>
                    <div class="bg-adminViewAccountHeaderColor2 flex justify-center items-center text-5xl font-bold">{{ $day }}</div>
                    <div>
                        <p class="text-submitButton font-bold text-xl">{{ $month }}, {{ $year }}</p>
                        <p class="text-xs mt-2">{{ $unavailableDate->unavailableDatesDesc }}</p>
                    </div>
                    <div>
                        <button class="btn-edit-item text-white bg-manageRestaurantSidebarColorActive inline-block text-center leading-6 w-6 h-6 rounded-full text-xs transition duration-200 ease-in-out hover:bg-hoverManageRestaurantSidebarColorActive hover:text-gray-300"><i class="fas fa-edit"></i></button>
                        <a href="/restaurant/manage-restaurant/time/unavailable-dates/delete/{{ $unavailableDate->id }}"  class="btn-delete mt-2 text-white bg-submitButton inline-block text-center leading-6 w-6 h-6 rounded-full text-xs transition duration-200 ease-in-out hover:bg-darkerSubmitButton hover:text-gray-300 "><i class="fas fa-trash-alt"></i></a>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($unavailableDates->isEmpty())
            <div class="mx-auto w-full mt-2 grid grid-cols-1 items-center bg-red-400 h-14 shadow-xl rounded-lg">
                <div class="text-center text-white">There are no Unavailable Dates right now</div>
            </div>
        @endif






        <div class="create-form font-Montserrat" id="create-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl">Add Unavailable Date</h1>
            <form action="/restaurant/manage-restaurant/time/unavailable-dates/add" method="POST">
                @csrf
                <div class="w-9/12 mx-auto mt-10">
                    <div class="grid grid-cols-storeHoursThreeCols justify-center">
                        <div>Date</div>
                        <div>:</div>
                        <div>
                            <input type="date" name="date" class="border rounded-md focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-storeHoursThreeCols justify-center mt-5">
                        <div>Description</div>
                        <div>:</div>
                        <div>
                            <textarea name="description" cols="30" class="border rounded-md focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button class="bg-submitButton hover:bg-darkerSubmitButton text-white hover:text-gray-300 rounded-md rounded-w-9/12 w-32 h-10 text-sm uppercase font-bold transition duration-200 ease-in-out " type="submit">Add</button>
                </div>
            </form>
        </div> 



        <div class="edit-form font-Montserrat" id="edit-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl">Edit Unavailable Date</h1>
            <form action="/restaurant/manage-restaurant/time/unavailable-dates/edit" method="POST">
                @csrf
                <input type="text" name="updateUnavailableDateId" id="updateUnavailableDateId" value="" hidden>
                <div class="w-9/12 mx-auto mt-10">
                    <div class="grid grid-cols-storeHoursThreeCols justify-center">
                        <div>Date</div>
                        <div>:</div>
                        <div>
                            <input type="date" id="updateDate" name="updateDate" class="border focus:border-black w-9/12 py-1 px-2 text-sm focus:outline-non text-gray-700" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-storeHoursThreeCols justify-center mt-5">
                        <div>Description</div>
                        <div>:</div>
                        <div>
                            <textarea name="updateDescription" id="updateDescription" cols="30" class="border focus:border-black w-9/12 py-1 px-2 text-sm focus:outline-non text-gray-700" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button class="bg-submitButton hover:bg-darkerSubmitButton text-white bg:text-gray-300 rounded-md rounded-w-9/12 w-32 h-10 text-sm uppercase font-bold transition duration-200 ease-in-out" type="submit">Update</button>
                </div>
            </form>
        </div> 
        <div class="overlay"></div>
    </div>
    
</div>
@endsection