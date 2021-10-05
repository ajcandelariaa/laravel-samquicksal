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
                <button id="btn-add-item" class="bg-submitButton text-white w-36 h-9 rounded-md"><i class="fas fa-plus mr-3"></i>Food Item</button>
            </div>
        </div>
        <div class="w-full bg-adminViewAccountHeaderColor2 mt-3">
            <div class="grid grid-cols-12 items-center text-center font-bold h-16 px-5 bg-adminViewAccountHeaderColor2 shadow-adminDownloadButton">
                <div class="col-span-1">Id</div>
                <div class="col-span-1">Image</div>
                <div class="col-span-2">Name</div>
                <div class="col-span-5">Description</div>
                <div class="col-span-1">Price</div>
                <div class="col-span-1">Edit</div>
                <div class="col-span-1">Delete</div>
            </div>
            @if (!$foodItems->isEmpty())
                @php
                    $count = 1
                @endphp
                @foreach ($foodItems as $foodItem)  
                    @if ($count % 2 == 0)
                        <div class="tr bg-manageFoodItemHeaderBgColor grid grid-cols-12 justify-items-center items-center h-10 px-5 mb-3">
                            <div class="td col-span-1">{{ $foodItem->id }}</div>
                            <div class="td col-span-1">
                                <span class="hidden">{{ $foodItem->foodItemImage }}</span>
                                <img src="{{ asset('uploads/restaurantAccounts/foodItem/'.$id.'/'.$foodItem->foodItemImage) }}" alt="foodItemImage" class="w-8 h-7">
                            </div>
                            <div class="td col-span-2">{{ $foodItem->foodItemName }}</div>
                            <div class="td col-span-5">{{ $foodItem->foodItemDescription }}</div>
                            <div class="td col-span-1">{{ $foodItem->foodItemPrice }}</div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/food-menu/food-item/edit/{{ $foodItem->id }}"><i class="fas fa-edit"></i></a>
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/food-menu/food-item/delete/{{ $foodItem->id }}" class="btn-delete"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                    @else
                        <div class="tr bg-white grid grid-cols-12 justify-items-center items-center h-10 px-5 mb-3">
                            <div class="td col-span-1">{{ $foodItem->id }}</div>
                            <div class="td col-span-1">
                                <span class="hidden">{{ $foodItem->foodItemImage }}</span>
                                <img src="{{ asset('uploads/restaurantAccounts/foodItem/'.$id.'/'.$foodItem->foodItemImage) }}" alt="foodItemImage" class="w-8 h-7">
                            </div>
                            <div class="td col-span-2">{{ $foodItem->foodItemName }}</div>
                            <div class="td col-span-5">{{ $foodItem->foodItemDescription }}</div>
                            <div class="td col-span-1">{{ $foodItem->foodItemPrice }}</div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/food-menu/food-item/edit/{{ $foodItem->id }}"><i class="fas fa-edit"></i></a>
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/food-menu/food-item/delete/{{ $foodItem->id }}" class="btn-delete"><i class="fas fa-trash-alt"></i></a>
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
            <h1 class="text-center text-submitButton font-bold text-2xl">Create Food Item</h1>
            <form action="/restaurant/manage-restaurant/food-menu/food-item/add" method="POST" enctype="multipart/form-data" id="food-item">
                @csrf
                <div class="grid grid-cols-5 w-9/12 gap-y-5 mx-auto mt-10">
                    <div class="col-span-1">Name</div>
                    <div class="col-span-1">:</div>
                    <div class="col-span-3">
                        <input type="text" name="foodName" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700">
                    </div>
                    
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
                </div>
            </form>
        </div> 
        <div class="overlay"></div>
    </div>
</div>
@endsection