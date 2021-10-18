@extends('restaurant.app2')

@section('content')
<div class="container mx-auto food-item">
    <div class="w-11/12 mx-auto mt-10">
        <a href="/restaurant/manage-restaurant/promo" class="mt-2 text-submitButton uppercase font-bold"><i class="fas fa-chevron-left mr-2"></i>Back</a>
        <div class="w-full bg-manageRestaurantSidebarColorActive mt-5 rounded-2xl pb-8 mb-10">
            <form action="/restaurant/manage-restaurant/promo/add" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex justify-between items-center px-4 py-3">
                    <div class="text-white font-bold uppercase text-xl">Add Promo</div>
                    <button id="btn-add-food-item" class="bg-submitButton text-white w-28 h-9 rounded-md">Add</button>
                </div>
                <div class=" w-full grid grid-cols-3 gap-1 bg-adminViewAccountHeaderColor2">
                    <div class="col-span-1 bg-white">
                        <div class="w-full px-4 mt-5">
                            <input type="file" name="promoImage" onchange="previewFile(this);" class="w-full border border-gray-400 text-sm text-gray-700 focus:outline-none focus:border-black">
                            <span class="mt-2 text-red-600 italic text-sm">@error('promoImage'){{ $message }}@enderror</span>

                        </div>
                        <div class="w-full px-4 mt-5">
                            <img src="{{ asset('images/defaultAccountImage.png') }}" id="previewImg" alt="promoImage">
                        </div>
                    </div>
                    <div class="col-span-2 bg-adminViewAccountHeaderColor2">
                        <div class="grid grid-cols-2">
                            <div class="uppercase pl-5 font-bold py-3">Title</div>
                            <div class="uppercase pl-5 font-bold py-3">VALIDITY PERIOD</div>
                        </div>

                        <div class="grid grid-cols-2 gap-1">
                            <div class="bg-white w-full p-4">
                                <input type="text" value="{{ old('promoTitle') }}" name="promoTitle" class="w-full border border-gray-400 py-1 px-2 text-sm text-gray-700 focus:outline-none focus:border-black h-5/6">
                                <span class="mt-2 text-red-600 italic text-sm">@error('promoTitle'){{ $message }}@enderror</span>
                            </div>
                            
                            <div class="bg-white w-full p-4 grid grid-cols-2 gap-3">
                                <div>
                                    <p>Start Date:</p>
                                    <input type="date" value="{{ old('promoStartDate') }}" name="promoStartDate" class="mt-2 w-full border border-gray-400 py-1 px-2 text-sm text-gray-700 focus:outline-none focus:border-black h-7">
                                    <span class="mt-2 text-red-600 italic text-sm">@error('promoStartDate'){{ $message }}@enderror</span>
                                </div>

                                <div>
                                    <p>End Date:</p>
                                    <input type="date" value="{{ old('promoEndDate') }}" name="promoEndDate" class="mt-2 w-full border border-gray-400 py-1 px-2 text-sm text-gray-700 focus:outline-none focus:border-black h-7">
                                    <span class="mt-2 text-red-600 italic text-sm">@error('promoEndDate'){{ $message }}@enderror</span>
                                </div>
                            </div>
                        </div>
    
                        <div class="bg-adminViewAccountHeaderColor2 uppercase pl-5 font-bold py-3">Description</div>
                        <div class="bg-white w-full p-4">
                            <textarea name="promoDescription" class="w-full border border-gray-400 py-1 px-2 text-sm text-gray-700 focus:outline-none focus:border-black h-14">{{ old('promoDescription') }}</textarea>
                            <span class="mt-2 text-red-600 italic text-sm">@error('promoDescription'){{ $message }}@enderror</span>
                        </div>
                        
                        <div class="bg-adminViewAccountHeaderColor2 uppercase pl-5 font-bold py-3">Mechanics</div>
                        <div class="bg-white w-full p-4">
                            <table id="ingredients_table" class="w-full">
                                <tr id="ingr_row1">
                                    <td>
                                        <textarea name="promoMechanics[]" class="w-full border border-gray-400 py-1 px-2 text-sm text-gray-700 focus:outline-none focus:border-black" placeholder="Enter Mechanics" rows="1">{{ old('promoMechanics.0') }}</textarea>
                                        <span class="mt-2 text-red-600 italic text-sm">@error('promoMechanics.0'){{ $message }}@enderror</span>
                                    </td>
                                </tr>
                            </table>
                            <div class="text-center mt-2">
                                <input type="button" class="mt-2 cursor-pointer bg-headerActiveTextColor text-white py-2 px-10 hover:bg-btnHoverColor" onclick="add_row_ingredient();" value="Add More">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection