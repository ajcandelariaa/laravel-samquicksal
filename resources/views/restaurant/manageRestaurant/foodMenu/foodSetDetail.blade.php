@extends('restaurant.app2')

@section('content')
<div class="container mx-auto food-set">
    @if (session()->has('added'))
        <script>
            Swal.fire(
                'Food Item Added',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('deleted'))
        <script>
            Swal.fire(
                'Food Item is Deleted in Food Set',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('empty'))
        <script>
            Swal.fire(
                'No Item Added',
                'Please select at least one food item',
                'error'
            );
        </script>
    @elseif (session()->has('allExisting'))
        <script>
            Swal.fire(
                'All food item are already exist',
                '',
                'error'
            );
        </script>
    @elseif (session()->has('someExisting'))
        <script>
            Swal.fire(
                'Food Item Added',
                'some food item are already exist',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10">
        <a href="/restaurant/manage-restaurant/food-menu/food-set" class="mt-2 text-submitButton uppercase font-bold"><i class="fas fa-chevron-left mr-2"></i>Back</a>
        <div class="w-full mt-3 text-right">
            <a href="/restaurant/manage-restaurant/food-menu/food-set/detail/edit/{{ $foodSet->id }}" class="text-white bg-manageRestaurantSidebarColorActive inline-block text-center leading-9 w-9 h-9 rounded-full mr-3"><i class="fas fa-edit"></i></a>
            <a href="/restaurant/manage-restaurant/food-menu/food-set/delete/{{ $foodSet->id }}" class="btn-delete text-white bg-submitButton inline-block text-center leading-9 w-9 h-9 rounded-full"><i class="fas fa-trash-alt"></i></a>
        </div>
        
        <div class="w-full mt-3 rounded-2xl shadow-adminDownloadButton">
            <div class="rounded-t-2xl bg-manageRestaurantSidebarColorActive text-white font-bold text-center w-full h-11 grid items-center">Food Set</div>
            <div class="rounded-b-xl grid grid-cols-foodSet px-4 bg-adminViewAccountHeaderColor2 py-7">
                <div class="self-center">
                    <img src="{{ asset('uploads/restaurantAccounts/foodSet/'.$id.'/'.$foodSet->foodSetImage) }}" alt="foodSetImage" class="w-40 h-40">
                </div>
                <div class="mt-4">
                    <div class="flex justify-between">
                        <p class="text-4xl uppercase font-bold">{{ $foodSet->foodSetName }}</p>
                        <p class="text-lg font-bold text-manageRestaurantSidebarColor">Price: <span class="text-red-800"> Php {{ $foodSet->foodSetPrice }} </span></p>
                    </div>
                    <p class="mt-3 text-sm">{{ $foodSet->foodSetDescription }}</p>
                </div>
            </div>
        </div>

        <div class="w-full mt-10 mb-12 rounded-2xl shadow-adminDownloadButton bg-adminViewAccountHeaderColor2">
            <div class="rounded-t-2xl bg-manageRestaurantSidebarColorActive w-full flex justify-between items-center px-4 py-3">
                <div class="text-white font-bold uppercase text-xl font-Montserrat">Food Items</div>
                <div class="">
                    <button id="btn-add-food-item" class="bg-submitButton text-white w-36 h-9 rounded-md font-Montserrat hover:bg-white hover:text-submitButton hover:shadow-xl transition duration-300 ease-in-out  "><i class="fas fa-plus mr-3"></i>Food Item</button>
                </div>
            </div>
            <div class="grid grid-cols-11 items-center text-center font-bold h-16 px-5 font-Montserrat">
                <div class="col-span-1">Image</div>
                <div class="col-span-1">Id</div>
                <div class="col-span-2">Name</div>
                <div class="col-span-5">Description</div>
                <div class="col-span-1">Price</div>
                <div class="col-span-1">Delete</div>
            </div>
            @if (!$foodSetItems->isEmpty())
                    @php
                        $count = 1
                    @endphp
                    @foreach ($foodSetItems as $foodSetItem)  
                        @foreach ($foodItems as $foodItem)
                            @if ($foodSetItem->foodItem_id == $foodItem->id)
                                @if ($count % 2 == 0)
                                    <div class="bg-manageFoodItemHeaderBgColor grid grid-cols-11 justify-items-center items-center h-10 px-5 mb-2">
                                        <div class="col-span-1">
                                            <img src="{{ asset('uploads/restaurantAccounts/foodItem/'.$id.'/'.$foodItem->foodItemImage) }}" alt="foodItemImage" class="w-8 h-7">
                                        </div>
                                        <div class="col-span-1">{{ $foodItem->id }}</div>
                                        <div class="col-span-2">{{ $foodItem->foodItemName }}</div>
                                        <div class="col-span-5">{{ $foodItem->foodItemDescription }}</div>
                                        <div class="col-span-1">{{ $foodItem->foodItemPrice }}</div>
                                        <div class="col-span-1">
                                            <a href="/restaurant/manage-restaurant/food-menu/food-set/item/delete/{{ $foodSet->id }}/{{ $foodSetItem->id }}" class="btn-delete2  "><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-white grid grid-cols-11 justify-items-center items-center h-10 px-5 mb-2">
                                        <div class="col-span-1">
                                            <img src="{{ asset('uploads/restaurantAccounts/foodItem/'.$id.'/'.$foodItem->foodItemImage) }}" alt="foodItemImage" class="w-8 h-7">
                                        </div>
                                        <div class="col-span-1">{{ $foodItem->id }}</div>
                                        <div class="col-span-2">{{ $foodItem->foodItemName }}</div>
                                        <div class="col-span-5">{{ $foodItem->foodItemDescription }}</div>
                                        <div class="col-span-1">{{ $foodItem->foodItemPrice }}</div>
                                        <div class="col-span-1">
                                            <a href="/restaurant/manage-restaurant/food-menu/food-set/item/delete/{{ $foodSet->id }}/{{ $foodSetItem->id }}" class="btn-delete2"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                        @php
                            $count++
                        @endphp
                    @endforeach
                @endif

                @if ($foodSetItems->isEmpty())
                    <div class="mx-auto w-full mt-2 grid grid-cols-1 items-center bg-red-400 h-14 shadow-xl rounded-b-lg">
                        <div class="text-center text-white">There are no Food Items in this Food Set right now</div>
                    </div>
                @endif
        </div>
    </div>

    <div class="add-form" id="add-form">
        <form action="/restaurant/manage-restaurant/food-menu/food-set/detail" method="POST" enctype="multipart/form-data">
            <div class="flex justify-between">
                <h1 class="font-bold text-xl text-white">FOOD ITEM INVENTORY</h1>
                <button class="bg-submitButton text-white rounded-md w-28 h-10 text-sm" type="submit">Add</button>
            </div>
            @csrf
            <input type="text" name="foodSetId" value="{{ $foodSet->id }}" hidden>
            <div class="w-full bg-adminViewAccountHeaderColor2 mt-3">
                <div class="grid grid-cols-12 items-center text-center font-bold h-16 px-5">
                    <div class="col-span-2 bg-white py-2">
                        <input type="checkbox" name="select-all" id="select-all" class="mr-4"> 
                        <label class="text-manageRestaurantSidebarColorActive">Select All</label>
                    </div>
                    <div class="col-span-1"> </div>
                    <div class="col-span-1">Id</div>
                    <div class="col-span-2">Name</div>
                    <div class="col-span-5">Description</div>
                    <div class="col-span-1">Price</div>
                </div>
                @if (!$foodItems->isEmpty())
                    @php
                        $count = 1
                    @endphp
                    @foreach ($foodItems as $foodItem)  
                        @if ($count % 2 == 0)
                            <div class="bg-manageFoodItemHeaderBgColor grid grid-cols-12 justify-items-center items-center h-10 px-5 mb-2">
                                <div class="col-span-2">
                                    <input type="checkbox" name="foodItem[]" value="{{ $foodItem->id }}">
                                </div>
                                <div class="col-span-1">
                                    <span class="hidden">{{ $foodItem->foodItemImage }}</span>
                                    <img src="{{ asset('uploads/restaurantAccounts/foodItem/'.$id.'/'.$foodItem->foodItemImage) }}" alt="foodItemImage" class="w-8 h-7">
                                </div>
                                <div class="col-span-1">{{ $foodItem->id }}</div>
                                <div class="col-span-2">{{ $foodItem->foodItemName }}</div>
                                <div class="col-span-5">{{ $foodItem->foodItemDescription }}</div>
                                <div class="col-span-1">{{ $foodItem->foodItemPrice }}</div>
                            </div>
                        @else
                            <div class="bg-white grid grid-cols-12 justify-items-center items-center h-10 px-5 mb-2">
                                <div class="col-span-2">
                                    <input type="checkbox" name="foodItem[]" value="{{ $foodItem->id }}">
                                </div>
                                <div class="col-span-1">
                                    <span class="hidden">{{ $foodItem->foodItemImage }}</span>
                                    <img src="{{ asset('uploads/restaurantAccounts/foodItem/'.$id.'/'.$foodItem->foodItemImage) }}" alt="foodItemImage" class="w-8 h-7">
                                </div>
                                <div class="col-span-1">{{ $foodItem->id }}</div>
                                <div class="col-span-2">{{ $foodItem->foodItemName }}</div>
                                <div class="col-span-5">{{ $foodItem->foodItemDescription }}</div>
                                <div class="col-span-1">{{ $foodItem->foodItemPrice }}</div>
                            </div>
                        @endif
                        @php
                            $count++
                        @endphp
                    @endforeach
                @endif

                @if ($foodItems->isEmpty())
                    <div class="mx-auto w-full mt-2 grid grid-cols-1 items-center bg-red-400 h-14 shadow-xl rounded-lg">
                        <div class="text-center text-white">There are no Food Item right now</div>
                    </div>
                @endif
            </div>
        </form>
    </div>
    <div class="overlay"></div>
</div>
@endsection