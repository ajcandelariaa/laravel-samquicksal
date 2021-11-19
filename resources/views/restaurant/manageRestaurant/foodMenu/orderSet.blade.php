@extends('restaurant.app2')

@section('content')
<div class="container mx-auto food-item">
    @if (session()->has('added'))
        <script>
            Swal.fire(
                'Order Set Added',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('deleted'))
        <script>
            Swal.fire(
                'Order Set Deleted',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10">
        <div class="flex justify-between w-full">
            <div>
                <form action="/restaurant/manage-restaurant/food-menu/order-set" method="GET">
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
                <button id="btn-add-item" class="bg-submitButton text-white w-36 h-9 rounded-md font-Montserrat hover:bg-btnHoverColor transition duration-300 ease-in-out"><i class="fas fa-plus mr-3"></i>Order Set</button>
            </div>
        </div>
        <div class="w-full mt-3">
            @if (!$orderSets->isEmpty())
                <div class="grid grid-cols-2 w-full gap-5">
                @foreach ($orderSets as $orderSet)  
                    <div class="grid grid-cols-foodSet px-5 py-4 h-48 rounded-xl shadow-adminDownloadButton bg-white col-span-1 hover:bg-adminViewAccountHeaderColor2">
                        <div class="self-center">
                            <img src="{{ asset('uploads/restaurantAccounts/orderSet/'.$id.'/'.$orderSet->orderSetImage) }}" alt="orderSetImage" class="w-40 h-40">
                        </div>
                        <div class="overflow-hidden">
                            <a href="/restaurant/manage-restaurant/food-menu/order-set/detail/{{ $orderSet->id }}" class="text-4xl uppercase font-bold hover:underline">{{ $orderSet->orderSetName }}</a>
                            <p class="mt-4 text-sm ">Description: {{ $orderSet->orderSetDescription }}</p>
                            <p class="mt-1 text-sm">Status: {{ $orderSet->status }}</p>
                            <p class="mt-1 text-sm">Available: {{ $orderSet->available }}</p>
                        </div>
                    </div>
                @endforeach
                </div>
            @endif
        </div>

        @if ($orderSets->isEmpty())
            <div class="mx-auto w-full mt-2 grid grid-cols-1 items-center bg-red-400 h-14 shadow-xl rounded-lg">
                <div class="text-center text-white">There are no Order Sets right now</div>
            </div>
        @endif

        <div class="mx-auto w-11/12 mt-6">
            {{ $orderSets->links() }}
        </div>

        <div class="create-form" id="create-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl font-Montserrat">Create Order Set</h1>
            <form action="/restaurant/manage-restaurant/food-menu/order-set/add" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- <div class="grid grid-cols-5 w-9/12 gap-y-5 mx-auto mt-10">
                    <div class="col-span-1">Name</div>
                    <div class="col-span-1">:</div>
                    <div class="col-span-3">
                        <input type="text" name="foodName" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700">
                    </div>
                    
                    <div class="col-span-1">Tagline</div>
                    <div class="col-span-1">:</div>
                    <div class="col-span-3"><input type="text" name="foodTagline" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>
                    
                    <div class="col-span-1">Description</div>
                    <div class="col-span-1">:</div>
                    <div class="col-span-3"><input type="text" name="foodDesc" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>
                    
                    <div class="col-span-1">Price</div>
                    <div class="col-span-1">:</div>
                    <div class="col-span-3"><input type="text" name="foodPrice" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>
                    
                    <div class="col-span-1">Image</div>
                    <div class="col-span-1">:</div>
                    <div class="col-span-3"><input type="file" name="foodImage" onchange="previewFile(this);" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>

                    <div class="col-span-full w-28 justify-self-center h-28">
                        <img src="{{ asset('images/defaultAccountImage.png') }}" alt="food-image" id="previewImg" class="bg-cover">
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button class="bg-submitButton text-white rounded-full w-32 h-10 text-sm" type="submit">Add</button>
                </div> --}}
                {{-- sample --}}
                <div class="flex flex-col w-9/12 gap-y-5 mx-auto mt-10 ">
                    <div class="flex flex-row -mb-1">
                        <h1 class="mr-3 font-Montserrat">Name:</h1>
                        <input type="text" name="foodName" class="font-Montserrat border focus:border-black rounded-md w-7/12 py-1 px-2 text-sm focus:outline-non text-gray-700 ">
                        <h1 class="ml-2 mr-2 font-Montserrat">Price:</h1>
                        <input type="text" name="foodPrice" class="font-Montserrat border focus:border-black rounded-md w-2/12 py-1 px-2 text-sm focus:outline-non text-gray-700">
                    </div>

                    <div class="flex flex-row">
                    <h1 class="mr-1 font-Montserrat">Tagline:</h1>
                    <input type="text" name="foodTagline" class="font-Montserrat border rounded-md focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>

                    <div class="-mb-3 -mt-3 font-Montserrat">Description: </div>
                    <textarea type="text" name="foodDesc" placeholder="Type description..." class="font-Montserrat p-5 mb-1 bg-white border rounded-md border-gray-200 shadow-sm h-24 w-full" id=""></textarea>
                    
                      <div class="justify-self-center flex flex-row">
                        <h1 class="mr-4 font-Montserrat">Image:</h1>
                        <div class="flex-row font-Montserrat"><input type="file" name="foodImage" onchange="previewFile(this);" class="border focus:border-black rounded-md w-full h-full py-1 px-2 text-sm focus:outline-non text-gray-700 "></div>
                     </div>
                     
                    <div class="mx-auto">
                    <img src="{{ asset('images/defaultAccountImage.png') }}" alt="food-image" id="previewImg" class="h-28 w-28">
                    </div>
                </div>
                <div class="text-center mt-2 mb-4">
                    <button class="bg-submitButton text-white rounded-full w-32 h-10 text-sm hover:bg-darkerSubmitButton hover:text-gray-300 transition duration-300 ease-in-out font-bold font-Montserrat" type="submit">ADD</button>
                </div>
                {{-- sample --}}
            </form>
        </div>

        <div class="overlay"></div>
    </div>
</div>
@endsection