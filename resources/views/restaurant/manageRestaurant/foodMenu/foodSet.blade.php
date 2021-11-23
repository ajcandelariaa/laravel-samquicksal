@extends('restaurant.app2')

@section('content')
<div class="container mx-auto food-item">
    @if (session()->has('added'))
        <script>
            Swal.fire(
                'Food Set Added',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('deleted'))
        <script>
            Swal.fire(
                'Food Set Deleted',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10 font-Montserrat">
        <div class="flex justify-between w-full">
            <div>
                <form action="/restaurant/manage-restaurant/food-menu/food-set" method="GET">
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
            <div>
                <button id="btn-add-item" class="bg-submitButton text-white w-36 h-9 rounded-md font-Montserrat hover:bg-btnHoverColor transition duration-200 ease-in-out "><i class="fas fa-plus mr-3"></i>Food Set</button>
            </div>
        </div>
        <div class="w-full mt-3">
            @if (!$foodSets->isEmpty())
                <div class="grid grid-cols-2 w-full gap-5">
                @foreach ($foodSets as $foodSet)  
                    <div class="grid grid-cols-foodSet px-5 py-4 h-48 rounded-xl shadow-adminDownloadButton bg-white col-span-1 hover:bg-adminViewAccountHeaderColor2">
                        <div class="self-center">
                            <img src="{{ asset('uploads/restaurantAccounts/foodSet/'.$id.'/'.$foodSet->foodSetImage) }}" alt="foodSetImage" class="w-40 h-40">
                        </div>
                        <div class="overflow-hidden">
                            <a href="/restaurant/manage-restaurant/food-menu/food-set/detail/{{ $foodSet->id }}" class="text-4xl uppercase font-bold hover:underline">{{ $foodSet->foodSetName }}</a>
                            <p class="mt-4 text-sm ">Description: {{ $foodSet->foodSetDescription }}</p>
                            <p class="mt-1 text-sm ">Status: {{ $foodSet->status }}</p>
                            <p class="mt-1 text-sm ">Available: {{ $foodSet->available }}</p>
                        </div>
                    </div>
                @endforeach
                </div>
            @endif
        </div>

        @if ($foodSets->isEmpty())
            <div class="mx-auto w-full mt-2 grid grid-cols-1 items-center bg-red-400 h-14 shadow-xl rounded-lg">
                <div class="text-center text-white">There are no Food Sets right now</div>
            </div>
        @endif

        <div class="mx-auto w-11/12 mt-6">
            {{ $foodSets->links() }}
        </div>

        <div class="create-form" id="create-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl font-Montserrat ">Create Food Set</h1>
            <form action="/restaurant/manage-restaurant/food-menu/food-set/add" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col w-9/12 gap-y-5 mx-auto mt-10 font-Montserrat ">
                    <div class="flex flex-row -mb-1">
                        <h1 class="mr-2">Name:</h1>
                        <input type="text" name="foodName" class="border focus:border-black rounded-md w-7/12 py-1 px-2 text-sm focus:outline-non text-gray-700 " required>
                        <h1 class="ml-4 mr-2">Price:</h1>
                        <input type="number" min="0" step=".01" name="foodPrice" class="border focus:border-black rounded-md w-2/12 py-1 px-2 text-sm focus:outline-non text-gray-700" required>
                    </div>

                    <div class="-mb-3 ">Description: </div>
                    <textarea type="text" name="foodDesc" placeholder="Type description..." class="p-2 mb-1 bg-white border rounded-md border-gray-200 shadow-sm h-24 w-full" id="" required></textarea>
                    
                      <div class="justify-self-center flex flex-row">
                        <h1 class="mr-4">Image:</h1>
                        <div class="flex-row"><input type="file" name="foodImage" onchange="previewFile(this);" class="border focus:border-black rounded-md w-full h-full py-1 px-2 text-sm focus:outline-non text-gray-700 " required></div>
                     </div>
                     
                    <div class="mx-auto">
                    <img src="{{ asset('images/defaultAccountImage.png') }}" alt="food-image" id="previewImg" class="h-28 w-28">
                    </div>
                </div>
                <div class="text-center mt-2 mb-4">
                    <button class="bg-submitButton text-white rounded-full w-32 h-10 text-sm hover:bg-darkerSubmitButton hover:text-gray-300 transition duration-200 ease-in-out font-bold" type="submit">ADD</button>
                </div>
            </form>
        </div>

        <div class="overlay"></div>
    </div>
</div>
@endsection