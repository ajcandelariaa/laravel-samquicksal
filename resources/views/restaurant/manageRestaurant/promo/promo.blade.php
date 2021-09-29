@extends('restaurant.app2')

@section('content')
<div class="container mx-auto food-item">
    @if (session()->has('added'))
        <script>
            Swal.fire(
                'Promo Added',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('deleted'))
        <script>
            Swal.fire(
                'Promo Deleted',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('posted'))
        <script>
            Swal.fire(
                'Promo Posted',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('drafted'))
        <script>
            Swal.fire(
                'Promo Drafted',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10">
        <div class="flex justify-between w-full">
            <div>
                <form action="/restaurant/manage-restaurant/promo" method="GET">
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
                <button id="btn-add-item" class="bg-submitButton text-white w-36 h-9 rounded-md"><i class="fas fa-plus mr-3"></i>Promo</button>
            </div>
        </div>
        <div class="w-full bg-adminViewAccountHeaderColor2 mt-3">
            <div class="grid grid-cols-12 items-center text-center font-bold h-16 px-5 bg-adminViewAccountHeaderColor2 shadow-adminDownloadButton">
                <div class="col-span-1">Id</div>
                <div class="col-span-1">Image</div>
                <div class="col-span-2">Title</div>
                <div class="col-span-5">Description</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1">Edit</div>
                <div class="col-span-1">Delete</div>
            </div>
            @if (!$promos->isEmpty())
                @php
                    $count = 1
                @endphp
                @foreach ($promos as $promo)  
                    @if ($count % 2 == 0)
                        <div class="tr bg-manageFoodItemHeaderBgColor grid grid-cols-12 justify-items-center items-center h-10 px-5 mb-3">
                            <div class="td col-span-1">{{ $promo->id }}</div>
                            <div class="td col-span-1">
                                <img src="{{ asset('uploads/restaurantAccounts/promo/'.$id.'/'.$promo->promoImage) }}" alt="promoImage" class="w-8 h-7">
                            </div>
                            <div class="td col-span-2">{{ $promo->promoTitle }}</div>
                            <div class="td col-span-5">{{ $promo->promoDescription }}</div>
                            <div class="td col-span-1">
                                @if ($promo->promoPosted == "Draft")
                                    <a href="/restaurant/manage-restaurant/promo/promoPost/{{ $promo->id }}" class="text-manageRestaurantSidebarColor">{{ $promo->promoPosted }}</a>
                                @else
                                    <a href="/restaurant/manage-restaurant/promo/promoDraft/{{ $promo->id }}" class="text-postedStatus">{{ $promo->promoPosted }}</a>
                                @endif
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/promo/edit/{{ $promo->id }}"><i class="fas fa-edit"></i></a>
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/promo/delete/{{ $promo->id }}"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                    @else
                        <div class="tr bg-white grid grid-cols-12 justify-items-center items-center h-10 px-5 mb-3">
                            <div class="td col-span-1">{{ $promo->id }}</div>
                            <div class="td col-span-1">
                                <img src="{{ asset('uploads/restaurantAccounts/promo/'.$id.'/'.$promo->promoImage) }}" alt="promoImage" class="w-8 h-7">
                            </div>
                            <div class="td col-span-2">{{ $promo->promoTitle }}</div>
                            <div class="td col-span-5">{{ $promo->promoDescription }}</div>
                            <div class="td col-span-1">
                                @if ($promo->promoPosted == "Draft")
                                    <a href="/restaurant/manage-restaurant/promo/promoPost/{{ $promo->id }}" class="text-manageRestaurantSidebarColor inline-block text-center leading-5 w-20 h-6 rounded-md border border-gray-400 hover:bg-gray-200">{{ $promo->promoPosted }}</a>
                                @else
                                    <a href="/restaurant/manage-restaurant/promo/promoDraft/{{ $promo->id }}" class="text-postedStatus inline-block text-center leading-5 w-20 h-6 rounded-md border border-gray-400 hover:bg-gray-200">{{ $promo->promoPosted }}</a>
                                @endif
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/promo/edit/{{ $promo->id }}"><i class="fas fa-edit"></i></a>
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/promo/delete/{{ $promo->id }}"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                    @endif
                    @php
                        $count++
                    @endphp
                @endforeach
            @endif
        </div>

        @if ($promos->isEmpty())
            <div class="mx-auto w-full mt-2 grid grid-cols-1 items-center bg-red-400 h-14 shadow-xl rounded-lg">
                <div class="text-center text-white">There are no Promos right now</div>
            </div>
        @endif

        <div class="mx-auto w-11/12 mt-6">
            {{ $promos->links() }}
        </div>

        <div class="create-form" id="create-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl">Create Promo</h1>
            <form action="/restaurant/manage-restaurant/promo/add" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-5 w-9/12 gap-y-5 mx-auto mt-10">
                    <div class="col-span-1">Title</div>
                    <div class="col-span-1">:</div>
                    <div class="col-span-3">
                        <input type="text" name="promoTitle" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700">
                    </div>
                    
                    <div class="col-span-1">Description</div>
                    <div class="col-span-1">:</div>
                    <div class="col-span-3"><input type="text" name="promoDescription" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>
                    
                    <div class="col-span-1">Mechanics</div>
                    <div class="col-span-1">:</div>
                    <div class="col-span-3"><input type="text" name="promoMechanics" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>
                    
                    <div class="col-span-1">Image</div>
                    <div class="col-span-1">:</div>
                    <div class="col-span-3"><input type="file" name="promoImage" onchange="previewFile(this);" class="border focus:border-black w-full py-1 px-2 text-sm focus:outline-non text-gray-700"></div>

                    <div class="col-span-full w-28 justify-self-center h-28">
                        <img src="{{ asset('images/defaultAccountImage.png') }}" alt="promoImage" id="previewImg" class="bg-cover">
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