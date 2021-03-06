@extends('restaurant.app2')

@section('content')
<div class="container mx-auto rewards">
    @if (session()->has('editedReward1'))
        <script>
            Swal.fire(
                'Reward 1 Updated',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('editedReward2'))
        <script>
            Swal.fire(
                'Reward 2 Updated',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('cantEdit'))
        <script>
            Swal.fire(
                'You can\'t edit right now',
                'You stil have a stamp card',
                'error'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10 font-Montserrat">
        <div class="grid grid-cols-2 gap-5 items-center">

            <div class="col-span-1 bg-white shadow-adminDownloadButton rounded-lg p-3 relative">
                <p class="uppercase text-xs">Reward 1</p>
                <div class="mt-3 mb-10 text-center text-2xl font-bold w-10/12 mx-auto">Discount <span class="underline">{{ $rewards[0]->rewardInput }}%</span> in a Total Bill</div>
                <div class="absolute top-3 right-3">
                    <button id="btn-reward1" class="btn-edit-item text-white hover:text-gray-300 hover:bg-hoverManageRestaurantSidebarColorActive bg-manageRestaurantSidebarColorActive  transition duration-300 ease-in-out inline-block text-center leading-9 w-9 h-9 rounded-full"><i class="fas fa-edit"></i></button>
                </div>
            </div>


            <div class="col-span-1 bg-white shadow-adminDownloadButton rounded-lg p-3 relative">
                <p class="uppercase text-xs">Reward 2</p>
                <div class="mt-3 mb-10 text-center text-2xl font-bold w-10/12 mx-auto">Free <span class="underline">{{ $rewards[1]->rewardInput }}</span> person in a group</div>
                <div class="absolute top-3 right-3">
                    <button id="btn-reward2" class="btn-edit-item text-white bg-manageRestaurantSidebarColorActive hover:text-gray-300 hover:bg-hoverManageRestaurantSidebarColorActive  transition duration-300 ease-in-out inline-block text-center leading-9 w-9 h-9 rounded-full"><i class="fas fa-edit"></i></button>
                </div>
            </div>


            <div class="col-span-1 bg-white shadow-adminDownloadButton rounded-lg p-3">
               <div class="flex justify-between">
                    <p class="uppercase text-xs">Reward 3</p>
                </div> 
                <div class="mt-3 mb-10 text-center text-2xl font-bold w-10/12 mx-auto">Half in the group will be free</div>
            </div>


            <div class="col-span-1 bg-white shadow-adminDownloadButton rounded-lg p-3">
               <div class="flex justify-between">
                    <p class="uppercase text-xs">Reward 4</p>
                </div> 
                <div class="mt-3 mb-10 text-center text-2xl font-bold w-10/12 mx-auto">All people in the group will be free</div>
            </div>
        </div>


        {{-- POP UP FORMS --}}
        <div class="reward1-form font-Montserrat">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl mt-16">Discount % in a Total Bill</h1>
            <form action="/restaurant/manage-restaurant/task-rewards/rewards/edit/reward1" id="reward1-form" method="POST">
                @csrf
                <div class="flex flex-row w-5/12 gap-y-5 mx-auto mt-10">
                    <h1 class="mr-3">Input:</h1>
                    <input type="number" min="1" max="100" name="discount" value="{{ $rewards[0]->rewardInput }}" class="border rounded-md focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700" required>
                </div>
                <div class="text-center mt-10">
                    <button id="btn-reward1-submit" class="bg-submitButton text-white rounded-full w-32 h-10 text-sm hover:bg-darkerSubmitButton hover:text-gray-300 transition duration-200 ease-in-out uppercase font-bold " type="submit">Update</button>
                </div>
            </form>
        </div>

        
        <div class="reward2-form font-Montserrat">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl mt-16">Free n<sup>th</sup> person in a group</h1>
            <form action="/restaurant/manage-restaurant/task-rewards/rewards/edit/reward2" id="reward2-form" method="POST">
                @csrf
                <div class="flex flex-row w-5/12 gap-y-5 mx-auto mt-10">
                    <h1 class="mr-3">Input:</h1>
                    <input type="number" min="1" max="20" name="free" value="{{ $rewards[1]->rewardInput }}" class="border rounded-md focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700" required>
                </div>
                <div class="text-center mt-10">
                    <button id="btn-reward2-submit" class="bg-submitButton text-white hover:bg-darkerSubmitButton hover:text-gray-300 rounded-full w-32 h-10 text-sm uppercase font-bold transition duration-200 ease-in-out " type="submit">Update</button>
                </div>
            </form>
        </div>




        <div class="overlay"></div>
    </div>
</div>
@endsection