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
    @elseif (session()->has('deleted'))
        <script>
            Swal.fire(
                'Promo Deleted',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('posted'))
        <script>
            Swal.fire(
                'Promo Posted',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('drafted'))
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
                <a href="/restaurant/manage-restaurant/promo/add" class="bg-submitButton text-white px-10 py-3 rounded-md font-Montserrat hover:bg-btnHoverColor transition duration-300 ease-in-out ">
                    <i class="fas fa-plus mr-3"></i>Promo
                </a>
            </div>
        </div>
        <div class="w-full bg-adminViewAccountHeaderColor2 mt-3">
            <div class="grid grid-cols-10 items-center text-center font-bold h-16 px-5 bg-adminViewAccountHeaderColor2 shadow-adminDownloadButton font-Montserrat">
                <div class="col-span-1">No.</div>
                <div class="col-span-3">Image</div>
                <div class="col-span-2">Title</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-1">Edit</div>
                <div class="col-span-1">Delete</div>
            </div>
            @if (!$promos->isEmpty())
                @php
                    $count = 1
                @endphp
                @foreach ($promos as $promo)  
                    @if ($count % 2 == 0)
                        <div class="tr bg-manageFoodItemHeaderBgColor grid grid-cols-10 justify-items-center items-center py-3 px-5 mb-3">
                            <div class="td col-span-1">
                                @if (isset($_GET['page']))
                                    @if ($_GET['page'] == 1)
                                        {{ $count }}
                                    @else
                                        {{ ((($_GET['page'] - 1) * 10) + $count) }}
                                    @endif
                                @else
                                    {{ $count }}
                                @endif
                            </div>
                            <div class="td col-span-3">
                                <img src="{{ asset('uploads/restaurantAccounts/promo/'.$id.'/'.$promo->promoImage) }}" alt="promoImage" class="w-12 h-12">
                            </div>
                            <div class="td col-span-2">{{ $promo->promoTitle }}</div>
                            <div class="td col-span-2">
                                @if ($promo->promoPosted == "Draft")
                                <a href="/restaurant/manage-restaurant/promo/promoPost/{{ $promo->id }}" class="text-manageRestaurantSidebarColor py-2 px-5 border-multiStepBoxBorder rounded-md border-gray-400 hover:bg-gray-200">{{ $promo->promoPosted }}</a>
                                @else
                                    <a href="/restaurant/manage-restaurant/promo/promoDraft/{{ $promo->id }}" class="text-postedStatus py-2 px-5 border-multiStepBoxBorder rounded-md border-postedStatus hover:bg-gray-200">{{ $promo->promoPosted }}</a>
                                @endif
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/promo/edit/{{ $promo->id }}"><i class="fas fa-edit hover:text-gray-400 transition duration-300 ease-in-out "></i></a>
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/promo/delete/{{ $promo->id }}" class="btn-delete hover:text-gray-400 transition duration-300 ease-in-out"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                    @else
                        <div class="tr bg-white grid grid-cols-10 justify-items-center items-center py-3 px-5 mb-3">
                            <div class="td col-span-1">
                                @if (isset($_GET['page']))
                                    @if ($_GET['page'] == 1)
                                        {{ $count }}
                                    @else
                                        {{ ((($_GET['page'] - 1) * 10) + $count) }}
                                    @endif
                                @else
                                    {{ $count }}
                                @endif
                            </div>
                            <div class="td col-span-3">
                                <img src="{{ asset('uploads/restaurantAccounts/promo/'.$id.'/'.$promo->promoImage) }}" alt="promoImage" class="w-12 h-12">
                            </div>
                            <div class="td col-span-2">{{ $promo->promoTitle }}</div>
                            <div class="td col-span-2">
                                @if ($promo->promoPosted == "Draft")
                                    <a href="/restaurant/manage-restaurant/promo/promoPost/{{ $promo->id }}" class="text-manageRestaurantSidebarColor py-2 px-5 border-multiStepBoxBorder rounded-md border-gray-400 hover:bg-gray-200">{{ $promo->promoPosted }}</a>
                                @else
                                    <a href="/restaurant/manage-restaurant/promo/promoDraft/{{ $promo->id }}" class="text-postedStatus py-2 px-5 border-multiStepBoxBorder rounded-md border-postedStatus hover:bg-gray-200">{{ $promo->promoPosted }}</a>
                                @endif
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/promo/edit/{{ $promo->id }}"><i class="fas fa-edit hover:text-gray-400 transition duration-300 ease-in-out "></i></a>
                            </div>
                            <div class="col-span-1">
                                <a href="/restaurant/manage-restaurant/promo/delete/{{ $promo->id }}" class="btn-delete hover:text-gray-400 transition duration-300 ease-in-out"><i class="fas fa-trash-alt"></i></a>
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

    </div>
</div>
@endsection