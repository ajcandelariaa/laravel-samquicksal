@extends('customer.app')

@section('content')
  <header>
    <div class="w-full bg-center bg-cover h-80" style="background-image: url({{asset('images/samgyupGrill.png')}});">
      <form class="container mx-auto flex justify-center items-center">
        <h1 class="ctext-center font-bold text-white text-4xl mt-16">What are you waiting for, Reserve now</label>
          <p class="my-10 max-w-lg"></p>
          <div class="sm:flex items-center bg-white rounded-lg overflow-hidden px-2 py-1 justify-between">
            <input class="text-base text-gray-400 flex-grow outline-none px-2 " type="text" placeholder="Restaurant name" />
              <button class="bg-submitButton hover:bg-adminLoginTextColor transition duration-300 ease-in-out text-white text-base rounded-lg px-4 py-2 font-thin">Search</button>
            </div>
          </div>
      </form>
    </div>
    </header>

  <section>
  <div class="flex p-16 justify-center items-center flex-col">
    <div class="mb-10">
      <h1 class="text-5xl font-black font-Montserrat">List of Restaurants</h1>
    </div>
    <div class="grid xl:grid-cols-2">

<div class="flex flex-col justify-center my-5 mx-4">

  @if ($restaurantSchedule != null)
    @for ($i = 0; $i < sizeOf($restaurantSchedule); $i++)
    <div class="relative flex flex-col md:flex-row md:space-x-5 space-y-3 md:space-y-0 rounded-xl shadow-lg p-3 max-w-xs md:max-w-3xl mx-auto border border-white bg-white">
      <div class="w-full md:w-1/3 bg-white grid place-items-center">
        @if ($restuarants[$i]->rLogo == null)
            <img src="{{ asset('images/resto-default.png') }}" alt="accountImage" class="rounded-xl w-10/12 ">
        @else
            <img src="{{ asset('uploads/restaurantAccounts/logo/'.$restuarants[$i]->id.'/'.$restuarants[$i]->rLogo) }}" alt="accountImage" class="rounded-xl w-10/12 ">
        @endif
      </div>
        <div class="w-full md:w-2/3 bg-white flex flex-col space-y-2 p-3">
          <div class="flex justify-between item-center">
            <div class="flex items-center"></div>
          </div>
          <h3 class="font-black text-gray-800 md:text-3xl text-xl">{{ $restuarants[$i]->rName }}</h3>
          <p class="md:text-lg text-gray-500 text-base">{{ $restuarants[$i]->rBranch.', '.$restuarants[$i]->rCity }}</p>
          @if ($restaurantSchedule[$i] == "Closed Now")
            <p class="text-xl font-black text-red-500">
              {{ $restaurantSchedule[$i] }}
            </p>
          @else
            <p class="text-xl font-black text-green-500">
              {{ $restaurantSchedule[$i] }}
            </p>
          @endif
           <div class="flex justify-end ">
            <a class="border-2 rounded-full py-1 px-8 bg-submitButton hover:bg-adminLoginTextColor transition duration-400 ease-in-out text-white text-sm" href="/customer/reservation/{{ $restuarants[$i]->id }}">View</a>
          </div>
        </div>
      </div>
    </div>
    @endfor
      
  @endif
	
  
  </div>
  
  
    </div>
  </div>
  </section>

@endsection