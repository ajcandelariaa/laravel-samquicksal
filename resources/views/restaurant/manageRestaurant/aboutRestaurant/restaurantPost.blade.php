@extends('restaurant.app2')

@section('content')
<div class="container mx-auto restaurant-about">
    @if (session()->has('added'))
        <script>
            Swal.fire(
                'Post Added',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('deleted'))
        <script>
            Swal.fire(
                'Post Deleted',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto my-10">
        <div class="w-full mt-5 bg-adminViewAccountHeaderColor2 p-5 rounded-xl shadow-adminDownloadButton">
            <form action="/restaurant/manage-restaurant/about/restaurant-post/add" method="POST" id="post-form" enctype="multipart/form-data">
                @csrf
                <textarea type="text" name="postDesc" placeholder="Write a post..." class="border border-gray-400 focus:border-black rounded-sm w-full p-2 text-sm text-gray-700 focus:outline-none" rows="5"></textarea>
                <div class="flex justify-between mt-3">
                    <div>
                        Image: <input type="file" name="postImage">
                        <br>
                        <span class="mt-2 text-red-600 italic text-sm">@error('postImage'){{ $message }}@enderror</span>
                    </div>
                    <button class="bg-submitButton text-white w-36 h-9 rounded-md font-Montserrat hover:text-gray-300 hover:bg-darkerSubmitButton transition duration-200 ease-in-out" id="btn-post-submit">Post</button>
                </div>
            </form>
        </div>
        <p class="text-4xl mt-14">POSTS</p>
        <div class="w-full mt-5">
            @if (!$posts->isEmpty())
                <div class="grid grid-cols-3 w-full gap-3">
                @foreach ($posts as $post)  
                    <div class="py-4 rounded-xl shadow-adminDownloadButton bg-white relative">
                        <div class="w-11/12 mx-auto">
                            <img src="{{ asset('uploads/restaurantAccounts/post/'.$id.'/'.$post->postImage) }}" alt="foodSetImage" class="h-80 w-full">
                        </div>
                        <div class="w-11/12 mx-auto pb-10">
                            <p class="mt-4 text-sm">{{ $post->postDesc }}</p>
                        </div>
                        <div class="absolute bottom-2 right-2">
                            <a href="/restaurant/manage-restaurant/about/restaurant-post/edit/{{ $post->id }}" class="text-white hover:text-gray-300 bg-manageRestaurantSidebarColorActive hover:bg-hoverManageRestaurantSidebarColorActive transition duration-200 ease-in-out inline-block text-center leading-9 w-9 h-9 rounded-full mr-1"><i class="fas fa-edit"></i></a>
                            <a href="/restaurant/manage-restaurant/about/restaurant-post/delete/{{ $post->id }}" class="btn-delete text-white hover:text-gray-300 bg-submitButton hover:bg-darkerSubmitButton transition duration-200 ease-in-out inline-block text-center leading-9 w-9 h-9 rounded-full"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </div>
                @endforeach
                </div>
            @endif
        </div>

        @if ($posts->isEmpty())
            <div class="mx-auto w-full mt-1 grid grid-cols-1 items-center bg-manageFoodItemHeaderBgColor h-14 shadow-sm rounded-lg">
                <div class="text-center text-multiStepBoxColor uppercase">There are no Posts right now</div>
            </div>
        @endif
    </div>
</div>
@endsection