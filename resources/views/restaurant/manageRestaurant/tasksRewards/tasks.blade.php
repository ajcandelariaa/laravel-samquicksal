@extends('restaurant.app2')

@section('content')
<div class="container mx-auto tasks">
    @if (session()->has('editedTask1'))
        <script>
            Swal.fire(
                'Task 1 Updated',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('editedTask2'))
        <script>
            Swal.fire(
                'Task 2 Updated',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('editedTask3'))
        <script>
            Swal.fire(
                'Task 3 Updated',
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
    
    <div class="w-11/12 mx-auto my-10 font-Montserrat">
        <div class="grid grid-cols-2 gap-5 items-center">

            <div class="col-span-1 bg-white shadow-adminDownloadButton rounded-lg p-3 relative">
                <p class="uppercase text-xs">Task 1</p>
                <div class="mt-3 mb-10 text-center text-2xl font-bold w-10/12 mx-auto">Spend <span class="underline">{{ $tasks[0]->taskInput }}</span> pesos in 1 visit only</div>
                <div class="absolute top-3 right-3">
                    <button id="btn-task1" class="btn-task1 text-white bg-manageRestaurantSidebarColorActive hover:text-gray-300 hover:bg-hoverManageRestaurantSidebarColorActive  transition duration-300 ease-in-outinline-block text-center leading-9 w-9 h-9 rounded-full"><i class="fas fa-edit"></i></button>
                </div>
            </div>


            <div class="col-span-1 bg-white shadow-adminDownloadButton rounded-lg p-3 relative">
                <p class="uppercase text-xs">Task 2</p>
                <div class="mt-3 mb-10 text-center text-2xl font-bold w-10/12 mx-auto">Bring <span class="underline">{{ $tasks[1]->taskInput }}</span> friends in our store</div>
                <div class="absolute top-3 right-3">
                    <button id="btn-task2" class="btn-task2 text-white bg-manageRestaurantSidebarColorActive hover:text-gray-300 hover:bg-hoverManageRestaurantSidebarColorActive  transition duration-300 ease-in-outinline-block text-center leading-9 w-9 h-9 rounded-full"><i class="fas fa-edit"></i></button>
                </div>
            </div>

            
            <div class="col-span-1 bg-white shadow-adminDownloadButton rounded-lg p-3 relative">
                 <p class="uppercase text-xs">Task 4</p>
                <div class="mt-3 mb-10 text-center text-2xl font-bold w-10/12 mx-auto">Order <span class="underline">{{ $tasks[2]->taskInput }}</span> add on/s per visit</div>
                <div class="absolute top-3 right-3">
                    <button id="btn-task3" class="btn-task3 text-white bg-manageRestaurantSidebarColorActive hover:text-gray-300 hover:bg-hoverManageRestaurantSidebarColorActive  transition duration-300 ease-in-outinline-block text-center leading-9 w-9 h-9 rounded-full"><i class="fas fa-edit"></i></button>
                </div>
            </div>
            

            <div class="col-span-1 bg-white shadow-adminDownloadButton rounded-lg p-3">
               <div class="flex justify-between">
                    <p class="uppercase text-xs">Task 3</p>
                </div> 
                <div class="mt-3 mb-10 text-center text-2xl font-bold w-10/12 mx-auto">Visit in our store</div>
            </div>


            <div class="col-span-1 bg-white shadow-adminDownloadButton rounded-lg p-3">
               <div class="flex justify-between">
                    <p class="uppercase text-xs">Task 5</p>
                </div> 
                <div class="mt-3 mb-10 text-center text-2xl font-bold w-10/12 mx-auto">Give a feedback/review per visit</div>
            </div>


            <div class="col-span-1 bg-white shadow-adminDownloadButton rounded-lg p-3">
               <div class="flex justify-between">
                    <p class="uppercase text-xs">Task 6</p>
                </div> 
                <div class="mt-3 mb-10 text-center text-2xl font-bold w-10/12 mx-auto">Pay using gcash</div>
            </div>


            <div class="col-span-1 bg-white shadow-adminDownloadButton rounded-lg p-3">
               <div class="flex justify-between">
                    <p class="uppercase text-xs">Task 7</p>
                </div> 
                <div class="mt-3 mb-10 text-center text-2xl font-bold w-10/12 mx-auto">Eat during lunch/dinner hours</div>
            </div>
        </div>

        

        


        {{-- POP UP FORMS --}}
        <div class="task1-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl font-Montserrat mt-16">Spend n<sup>th</sup> pesos in 1 visit only</h1>
            <form action="/restaurant/manage-restaurant/task-rewards/tasks/edit/task1" method="POST">
                @csrf
                <div class="flex flex-row w-5/12 gap-y-5 mx-auto mt-10">
                    <h1 class="mr-3 text-lg">Input:</h1>
                    <input type="number" min="1" name="task1" value="{{ $tasks[0]->taskInput }}" class="border rounded-md focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700" required>
                </div>
                <div class="text-center mt-10">
                    <button class="bg-submitButton text-white hover:bg-darkerSubmitButton hover:text-gray-300 rounded-full w-32 h-10 text-sm uppercase font-bold transition duration-200 ease-in-out  " type="submit">Update</button>
                </div>
            </form>
        </div>

        
        <div class="task2-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl font-Montserrat mt-16">Free n<sup>th</sup> person in a group</h1>
            <form action="/restaurant/manage-restaurant/task-rewards/tasks/edit/task2" method="POST">
                @csrf
                <div class="flex flex-row w-5/12 gap-y-5 mx-auto mt-10">
                    <h1 class="mr-3 text-lg">Input:</h1>
                    <input type="number" min="1" name="task2" value="{{ $tasks[1]->taskInput }}" class="border rounded-md focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700" required>
                </div>
                <div class="text-center mt-10">
                    <button class="bg-submitButton text-white hover:bg-darkerSubmitButton hover:text-gray-300 rounded-full w-32 h-10 text-sm uppercase font-bold transition duration-200 ease-in-out  " type="submit">Update</button>
                </div>
            </form>
        </div>

        
        <div class="task3-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold ">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl font-Montserrat mt-16">Order n<sup>th</sup> add on/s per visit</h1>
            <form action="/restaurant/manage-restaurant/task-rewards/tasks/edit/task3" method="POST">
                @csrf
                <div class="flex flex-row w-5/12 gap-y-5 mx-auto mt-10">
                    <h1 class="mr-3 text-lg">Input:</h1>
                    <input type="number" min="1" name="task3" value="{{ $tasks[2]->taskInput }}" class="border rounded-md focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700" required>
                </div>
                <div class="text-center mt-10">
                    <button class="bg-submitButton text-white hover:bg-darkerSubmitButton hover:text-gray-300 rounded-full w-32 h-10 text-sm uppercase font-bold transition duration-200 ease-in-out  " type="submit">Update</button>
                </div>
            </form>
        </div>




        <div class="overlay"></div>
    </div>
</div>
@endsection