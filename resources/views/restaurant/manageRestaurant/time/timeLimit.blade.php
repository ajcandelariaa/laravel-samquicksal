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
    <div class="w-11/12 mx-auto mt-10">
        <div class="w-full mt-3 rounded-2xl shadow-adminDownloadButton items-center bg-manageRestaurantSidebarColorActive pb-7">
            <div class="flex justify-between rounded-t-2xl px-5 py-4 items-center">
                <div class="text-white font-bold uppercase text-xl">Dine in Time limit</div>
                <a href="/restaurant/manage-restaurant/time/time-limit/edit" class="bg-manageRestaurantSidebarColor text-white w-36 h-9 leading-9 text-center rounded-md">Edit</a>
            </div>
            <div class="bg-white py-32">
                <div class="w-full mx-auto text-center">
                    @if ($account->rTimeLimit == 0)
                        <h1 class="uppercase text-5xl">No Time Limit</h1>
                    @else
                        <h1 class="uppercase text-5xl">{{ $account->rTimeLimit }} hours</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection