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
    @endif
    @if (session()->has('deleted'))
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
            <form action="/restaurant/manage-restaurant/about/restaurant-post/add" method="POST" enctype="multipart/form-data">
                @csrf
                <textarea type="text" name="postDesc" placeholder="Write a post..." class="border border-gray-400 focus:border-black rounded-sm w-full p-2 text-sm text-gray-700 focus:outline-none" rows="5"></textarea>
                <div class="flex justify-between mt-3">
                    <input type="file" name="postImage">
                    <button class="bg-submitButton text-white w-32 h-9 rounded-md">Post</button>
                </div>
            </form>
        </div>
        <p class="text-4xl mt-5">POSTS</p>
        <div class="w-full mt-5">
            @if (!$posts->isEmpty())
                <div class="grid grid-cols-1 w-full gap-3">
                @foreach ($posts as $post)  
                    <div class="grid grid-cols-postGrid px-5 py-4 h-52 rounded-xl shadow-adminDownloadButton bg-white">
                        <div class="self-center">
                            <img src="{{ asset('uploads/restaurantAccounts/post/'.$id.'/'.$post->postImage) }}" alt="foodSetImage" class="w-44 h-44">
                        </div>
                        <div class="overflow-hidden">
                            <p class="mt-4 text-sm "> {{ $post->postDesc }} </p>
                        </div>
                        <div class="place-self-end">
                            <a href="/restaurant/manage-restaurant/about/restaurant-post/edit/{{ $post->id }}" class="text-white bg-manageRestaurantSidebarColorActive inline-block text-center leading-9 w-9 h-9 rounded-full mr-1"><i class="fas fa-edit"></i></a>
                            <a href="/restaurant/manage-restaurant/about/restaurant-post/delete/{{ $post->id }}" class="btn-delete text-white bg-red-800 inline-block text-center leading-9 w-9 h-9 rounded-full"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </div>
                @endforeach
                </div>
            @endif
        </div>

        @if ($posts->isEmpty())
            <div class="mx-auto w-full mt-2 grid grid-cols-1 items-center bg-red-400 h-14 shadow-xl rounded-lg">
                <div class="text-center text-white">There are no Posts right now</div>
            </div>
        @endif
    </div>
</div>
@endsection