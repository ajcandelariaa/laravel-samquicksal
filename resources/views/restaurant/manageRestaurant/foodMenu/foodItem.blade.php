@extends('restaurant.app2')

@section('content')
<div class="container mx-auto food-item">
    @if (session()->has('added'))
        <script>
            Swal.fire(
                'Item Added',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('deleted'))
        <script>
            Swal.fire(
                'Item Deleted',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10">
        <div class="flex justify-between w-full">
            <div>
                <form action="/restaurant/manage-restaurant/food-menu/food-item" method="GET">
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
                <button id="btn-add-item" class="bg-submitButton text-white w-36 h-9 rounded-md font-Montserrat hover:bg-btnHoverColor transition duration-200 ease-in-out "><i class="fas fa-plus mr-3"></i>Food Item</button>
            </div>
        </div>
        <div class="w-full bg-adminViewAccountHeaderColor2 mt-3">
            <div class="grid grid-cols-12 items-center text-center font-bold h-16 px-5 bg-adminViewAccountHeaderColor2 shadow-adminDownloadButton font-Montserrat">
                <div class="col-span-1">No.</div>
                <div class="col-span-3">Image</div>
                <div class="col-span-3">Name</div>
                <div class="col-span-3">Price</div>
                <div class="col-span-1">Edit</div>
                <div class="col-span-1">Delete</div>
            </div>
            @if (!$foodItems->isEmpty())
                @php
                    $count = 1
                @endphp
                @foreach ($foodItems as $foodItem)  
                    @if ($count % 2 == 0)
                        <div class="tr bg-manageFoodItemHeaderBgColor grid grid-cols-12 justify-items-center items-center py-3 px-5 mb-3">
                            <div class="td col-span-1">{{ $count }}</div>
                            <div class="td col-span-3">
                                <span class="hidden">{{ $foodItem->foodItemImage }}</span>
                                <img src="{{ asset('uploads/restaurantAccounts/foodItem/'.$id.'/'.$foodItem->foodItemImage) }}" alt="foodItemImage" class="w-16 h-16">
                            </div>
                            <div class="td col-span-3">{{ $foodItem->foodItemName }}</div>
                            <div class="td col-span-3">{{ $foodItem->foodItemPrice }}</div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/food-menu/food-item/edit/{{ $foodItem->id }}"><i class="fas fa-edit hover:text-gray-400 transition duration-200 ease-in-out"></i></a>
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/food-menu/food-item/delete/{{ $foodItem->id }}" class="btn-delete"><i class="fas fa-trash-alt hover:text-gray-400 transition duration-200 ease-in-out"></i></a>
                            </div>
                        </div>
                    @else
                        <div class="tr bg-white grid grid-cols-12 justify-items-center items-center py-3 px-5 mb-3">
                            <div class="td col-span-1">{{ $count }}</div>
                            <div class="td col-span-3">
                                <span class="hidden">{{ $foodItem->foodItemImage }}</span>
                                <img src="{{ asset('uploads/restaurantAccounts/foodItem/'.$id.'/'.$foodItem->foodItemImage) }}" alt="foodItemImage" class="w-16 h-16">
                            </div>
                            <div class="td col-span-3">{{ $foodItem->foodItemName }}</div>
                            <div class="td col-span-3">{{ $foodItem->foodItemPrice }}</div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/food-menu/food-item/edit/{{ $foodItem->id }}"><i class="fas fa-edit hover:text-gray-400 transition duration-200 ease-in-out"></i></a>
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/food-menu/food-item/delete/{{ $foodItem->id }}" class="btn-delete"><i class="fas fa-trash-alt hover:text-gray-400 transition duration-200 ease-in-out"></i></a>
                            </div>
                        </div>
                    @endif
                    @php
                        $count++
                    @endphp
                @endforeach
            @endif
        </div>

        @if ($foodItems->isEmpty())
            <div class="mx-auto w-full mt-2 grid grid-cols-1 items-center bg-red-400 h-14 shadow-xl rounded-lg">
                <div class="text-center text-white">There are no Food Item right now</div>
            </div>
        @endif

        <div class="mx-auto w-11/12 mt-6">
            {{ $foodItems->links() }}
        </div>

        <div class="create-form" id="create-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold font-Montserrat text-2xl">Create Food Item</h1>
            <form action="/restaurant/manage-restaurant/food-menu/food-item/add" method="POST" enctype="multipart/form-data" id="food-item">
                @csrf
                {{-- <div class="grid grid-cols-5 w-9/12 gap-y-5 mx-auto mt-10">
                    <div class="col-span-1">Name :</div>
                    <div class="col-span-1">:</div>
                    <div class="col-span-3"><input type="text" name="foodName" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>

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
                </div> --}}
                {{-- sample --}}
                {{-- <div class="grid grid-cols-2 w-9/12 gap-y-5 mx-auto mt-10">
                    <div class="col-span-1">Name:</div>
                    <div class="col-span-1"><input type="text" name="foodName" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>

                    <div class="col-span-1">Description:</div>
                    <div class="col-span-1"><input type="text" name="foodDesc" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>
                    
                    <div class="col-span-1">Price:</div>
                    <div class="col-span-1"><input type="text" name="foodPrice" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>
                    
                    <div class="col-span-1">Image:</div>
                    <div class="col-span-1"><input type="file" name="foodImage" onchange="previewFile(this);" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>

                    <div class="col-span-full w-28 justify-self-center h-28">
                        <img src="{{ asset('images/defaultAccountImage.png') }}" alt="food-image" id="previewImg" class="bg-cover">
                    </div>
                </div> --}}
                 {{-- sample --}}
                <div class="flex flex-col w-9/12 gap-y-5 mx-auto mt-10 font-Montserrat ">
                    <div class="flex flex-row -mb-1">
                        <h1 class="mr-2">Name:</h1>
                        <input type="text" name="foodName" class="border focus:border-black rounded-md w-7/12 py-1 px-2 text-sm focus:outline-non text-gray-700 ">
                        <h1 class="ml-4 mr-2">Price:</h1>
                        <input type="text" name="foodPrice" class="border focus:border-black rounded-md w-2/12 py-1 px-2 text-sm focus:outline-non text-gray-700">
                    </div>

                    <div class="-mb-3 ">Description: </div>
                    <textarea type="text" name="foodDesc" placeholder="Type description..." class="p-5 mb-1 bg-white border rounded-md border-gray-200 shadow-sm h-24 w-full" id=""></textarea>
                    
                      <div class="justify-self-center flex flex-row">
                        <h1 class="mr-4">Image:</h1>
                        <div class="flex-row"><input type="file" name="foodImage" onchange="previewFile(this);" class="border focus:border-black rounded-md w-full h-full py-1 px-2 text-sm focus:outline-non text-gray-700 "></div>
                     </div>
                     
                    <div class="mx-auto">
                    <img src="{{ asset('images/defaultAccountImage.png') }}" alt="food-image" id="previewImg" class="h-28 w-28">
                    </div>
                </div>
                <div class="text-center mt-2 mb-4">
                    <button class="bg-submitButton text-white rounded-full w-32 h-10 text-sm hover:bg-darkerSubmitButton hover:text-gray-300 transition duration-200 ease-in-out font-bold font-Montserrat" type="submit">ADD</button>
                </div>
            </form>
        </div> 
        <div class="overlay"></div>
    </div>
</div>
@endsection