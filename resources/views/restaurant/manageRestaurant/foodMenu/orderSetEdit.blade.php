@extends('restaurant.app2')

@section('content')
<div class="container mx-auto food-item">
    @if (session()->has('edited'))
        <script>
            Swal.fire(
                'Order Set Updated',
                '',
                'success'
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
    <div class="w-11/12 mx-auto mt-10">
        <a href="/restaurant/manage-restaurant/food-menu/order-set/detail/{{ $orderSet->id }}" class="mt-2 text-submitButton uppercase font-bold"><i class="fas fa-chevron-left mr-2"></i>Back</a>
        <div class="w-full bg-manageRestaurantSidebarColorActive mt-5 rounded-2xl pb-8 mb-10">
            <form action="/restaurant/manage-restaurant/food-menu/order-set/edit/{{ $orderSet->id }}" id="orderSet-edit-form" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="flex justify-between items-center px-4 py-3">
                    <div class="text-white font-bold uppercase text-xl">Update Order Set</div>
                    <button id="btn-add-food-item" class="bg-submitButton text-white w-28 h-9 rounded-md">Save</button>
                </div>
                <div class=" w-full grid grid-cols-3 gap-1 bg-adminViewAccountHeaderColor2">
                    <div class="col-span-1 bg-white">
                        <div class="w-full px-4 mt-5">
                            <input type="file" name="foodImage" onchange="previewFile(this);" class="w-full border border-gray-400 text-sm text-gray-700 focus:outline-none focus:border-black">
                        </div>
                        <div class="w-full px-4 mt-5">
                            <img src="{{ asset('uploads/restaurantAccounts/orderSet/'.$resAccid.'/'.$orderSet->orderSetImage) }}" id="previewImg" alt="orderSetImage">
                        </div>
                    </div>
                    <div class="col-span-2 bg-black">
                        <div class="bg-adminViewAccountHeaderColor2 uppercase pl-5 font-bold py-3">Name</div>
                        <div class="bg-white w-full p-4">
                            <input type="text" name="foodName" class="w-full border border-gray-400 py-1 px-2 text-sm text-gray-700 focus:outline-none focus:border-black h-14" value="{{ $orderSet->orderSetName }}">
                            <span class="mt-2 text-red-600 italic text-sm">@error('foodName'){{ "Order Set Name is required" }}@enderror</span>
                        </div>
                        
                        <div class="bg-adminViewAccountHeaderColor2 uppercase pl-5 font-bold py-3">Tagline</div>
                        <div class="bg-white w-full p-4">
                            <input type="text" name="foodTagline" class="w-full border border-gray-400 py-1 px-2 text-sm text-gray-700 focus:outline-none focus:border-black h-14" value="{{ $orderSet->orderSetTagline }}">
                            <span class="mt-2 text-red-600 italic text-sm">@error('foodTagline'){{ "Order Set Tagline is required" }}@enderror</span>
                        </div>
    
                        <div class="bg-adminViewAccountHeaderColor2 uppercase pl-5 font-bold py-3">DESCRIPTION</div>
                        <div class="bg-white w-full p-4">
                            <textarea name="foodDesc" class="w-full border border-gray-400 y-1 px-2 text-sm text-gray-700 focus:outline-none focus:border-black h-14">{{ $orderSet->orderSetDescription }}</textarea>
                            <span class="mt-2 text-red-600 italic text-sm">@error('foodDesc'){{ "Order Set Description is required" }}@enderror</span>
                        </div>
                        
                        <div class="bg-adminViewAccountHeaderColor2 uppercase pl-5 font-bold py-3">Price</div>
                        <div class="bg-white w-full p-4">
                            <input type="number" min="0" step=".01" name="foodPrice" class="w-full border border-gray-400 py-1 px-2 text-sm text-gray-700 focus:outline-none focus:border-black h-14" value="{{ $orderSet->orderSetPrice }}">
                            <span class="mt-2 text-red-600 italic text-sm">@error('foodPrice'){{ "Order Set Price is required" }}@enderror</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection