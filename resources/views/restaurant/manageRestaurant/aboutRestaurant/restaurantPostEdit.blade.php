@extends('restaurant.app2')

@section('content')
<div class="container mx-auto food-item">
    @if (session()->has('edited'))
        <script>
            Swal.fire(
                'Post Updated',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10 font-Montserrat ">
        <a href="/restaurant/manage-restaurant/about/restaurant-post" class="mt-2 text-submitButton hover:text-darkerSubmitButton transition duration-200 ease-in-out uppercase font-bold"><i class="fas fa-chevron-left mr-2"></i>Back</a>
        <div class="w-full bg-manageRestaurantSidebarColorActive mt-5 rounded-2xl pb-8 mb-10">
            <form action="/restaurant/manage-restaurant/about/restaurant-post/edit/{{ $post->id }}" method="POST" enctype="multipart/form-data" id="post-edit-form">
                @csrf
                <div class="flex justify-between items-center px-4 py-3">
                    <div class="text-white font-bold uppercase text-xl">Update Post</div>
                    <button id="btn-add-food-item" class="bg-submitButton hover:bg-darkerSubmitButton text-white hover:text-gray-300 transition duration-200 ease-in-out w-28 h-9 rounded-md">Save</button>
                </div>
                <div class=" w-full grid grid-cols-3 gap-1 bg-adminViewAccountHeaderColor2">
                    <div class="col-span-1 bg-white">
                        <div class="w-full px-4 mt-5">
                            <input type="file" name="postImage" onchange="previewFile(this);" class="w-full border border-gray-400 text-sm text-gray-700 focus:outline-none focus:border-black">
                            <span class="mt-2 text-red-600 italic text-sm">@error('postImage'){{ $message }}@enderror</span>
                        </div>
                        <div class="w-full px-4 mt-5">
                            <img src="{{ asset('uploads/restaurantAccounts/post/'.$resAccid.'/'.$post->postImage) }}" id="previewImg" alt="orderSetImage">
                        </div>
                    </div>
                    <div class="col-span-2 bg-white">
                        <div class="bg-adminViewAccountHeaderColor2 uppercase pl-5 font-bold py-3">Description</div>
                        <div class="bg-white w-full p-4">
                            <textarea type="text" name="postDesc" placeholder="Write a post..." class="border border-gray-400 focus:border-black rounded-sm w-full p-2 text-sm text-gray-700 focus:outline-none" rows="5">{{ $post->postDesc }}</textarea>
                            <span class="mt-2 text-red-600 italic text-sm">@error('postDesc'){{ "Post Description is required" }}@enderror</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection