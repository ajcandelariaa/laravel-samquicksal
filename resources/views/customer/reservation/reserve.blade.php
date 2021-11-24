@extends('customer.app')

@section('content')

    {{-- Header --}}
    <header>
    <div class="w-full bg-center bg-cover h-80" style="background-image: url({{asset('images/samgyupGrill.png')}});">
    
        <div class="container flex items-center justify-start w-full h-full">
            <div class="md:ml-32 flex flex-col md:flex-row text-white mx-auto md:mx-0 ">
              @if ($restaurant->rLogo == null)
                <img src="{{ asset('images/resto-default.png') }}" alt="accountImage" class="rounded-xl w-10/12 ">
              @else
                <img src="{{ asset('uploads/restaurantAccounts/logo/'.$restaurant->id.'/'.$restaurant->rLogo) }}" alt="accountImage" class="w-32 xl:w-52 rounded-full mx-auto">
              @endif
               <div class="flex flex-col items-center md:items-start md:mt-20 pl-5">
               <h1 class="text-3xl font-bold">{{ $restaurant->rName }}</h1>
               <p class="text-base ">{{ $restaurant->rBranch.', '.$restaurant->rCity }}</p>
               </div>
            </div>
        </div>
    </div>
    </header>
 <div class="flex justify-center items-center w-full">
	<div x-data="setup()">
        <div class="grid grid-cols-4 gap-x-1 w-full px-1 mt-1 text-center font-bold uppercase ">
			<template x-for="(tab, index) in tabs" :key="index">
				<div class="w-full py-3 rounded-sm cursor-pointer "
					:class="activeTab===index ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton' : ''" @click="activeTab = index"
					x-text="tab"></div>
			</template>
		</div>

		<div class=" w-screen">
      {{-- About --}}
			<div x-show="activeTab===0">Content 1</div>
      {{-- Review --}}
			<div x-show="activeTab===1">

        <section>
          <div class="relative items-center w-full px-5 py-12 mx-auto  md:px-12  max-w-7xl">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
              <div class="flex flex-col w-full max-w-lg p-8 text-left shadow-2xl  lg:mx-auto rounded-xl">
                <p class="mx-auto text-base leading-relaxed text-black"> The Food is very good here, It's a must vist samgyup restaurant. Everybody was so nice! </p>
                </h2>
                <i class="fas fa-user-circle text-5xl mt-4 mb-2"></i>
                <h1>Wesley Delacruz</h1>
              </div>
              <div class="flex flex-col w-full max-w-lg p-8 text-left shadow-2xl  lg:mx-auto rounded-xl">
                <p class="mx-auto text-base leading-relaxed text-black"> The Food is very good here, It's a must vist samgyup restaurant. Everybody was so nice! </p>
                </h2>
                <i class="fas fa-user-circle text-5xl mt-4 mb-2"></i>
                <h1>Wesley Delacruz</h1>
              </div>
              <div class="flex flex-col w-full max-w-lg p-8 text-left shadow-2xl  lg:mx-auto rounded-xl">
                <p class="mx-auto text-base leading-relaxed text-black"> The Food is very good here, It's a must vist samgyup restaurant. Everybody was so nice! </p>
                </h2>
                <i class="fas fa-user-circle text-5xl mt-4 mb-2"></i>
                <h1>Wesley Delacruz</h1>
              </div>
              <div class="flex flex-col w-full max-w-lg p-8 text-left shadow-2xl  lg:mx-auto rounded-xl">
                <p class="mx-auto text-base leading-relaxed text-black"> The Food is very good here, It's a must vist samgyup restaurant. Everybody was so nice! </p>
                </h2>
                <i class="fas fa-user-circle text-5xl mt-4 mb-2"></i>
                <h1>Wesley Delacruz</h1>
              </div>
              <div class="flex flex-col w-full max-w-lg p-8 text-left shadow-2xl  lg:mx-auto rounded-xl">
                <p class="mx-auto text-base leading-relaxed text-black"> The Food is very good here, It's a must vist samgyup restaurant. Everybody was so nice! </p>
                </h2>
                <i class="fas fa-user-circle text-5xl mt-4 mb-2"></i>
                <h1>Wesley Delacruz</h1>
              </div>
              <div class="flex flex-col w-full max-w-lg p-8 text-left shadow-2xl  lg:mx-auto rounded-xl">
                <p class="mx-auto text-base leading-relaxed text-black"> The Food is very good here, It's a must vist samgyup restaurant. Everybody was so nice! </p>
                </h2>
                <i class="fas fa-user-circle text-5xl mt-4 mb-2"></i>
                <h1>Wesley Delacruz</h1>
              </div>
            </div>
          </div>
        </section>
      
      </div>
      {{-- Promo --}}
			<div x-show="activeTab===2">
          <div class="px-20 py-10">
            <div class="block md:flex justify-between md:-mx-2">
              <div class="w-full lg:w-1/3 md:mx-2 mb-4 md:mb-0">
                <div class="bg-white rounded-lg overflow-hidden shadow relative">
                  <img class="h-full w-full object-cover object-center" src="{{ asset('images/promo1.png') }}" alt="promo1">
                  <div class="p-4 h-auto md:h-40 lg:h-48">
                    <a href="#" class="block text-blue-500 hover:text-blue-600 font-semibold mb-2 text-lg md:text-base lg:text-lg">
                      Everything Opening Promo
                    </a>
                    <div class="text-gray-600 text-sm leading-relaxed block md:text-xs lg:text-sm">
                      Validity Period: January 10, 2021 - April 28, 2021
                    </div>
                    <div class="relative mt-2 lg:absolute bottom-0 mb-4">
                      <a class="inline bg-submitButton py-2 px-6 rounded-full  text-white hover:bg-adminLoginTextColor transition duration-300 ease-in-out" href="/customer/view-promo">View</a>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="w-full lg:w-1/3 md:mx-2 mb-4 md:mb-0">
                <div class="bg-white rounded-lg overflow-hidden shadow relative">
                  <img class="h-full w-full object-cover object-center" src="{{ asset('images/promo1.png') }}" alt="promo1">
                  <div class="p-4 h-auto md:h-40 lg:h-48">
                    <a href="#" class="block text-blue-500 hover:text-blue-600 font-semibold mb-2 text-lg md:text-base lg:text-lg">
                      Everything Opening Promo
                    </a>
                    <div class="text-gray-600 text-sm leading-relaxed block md:text-xs lg:text-sm">
                      Validity Period: January 10, 2021 - April 28, 2021
                    </div>
                    <div class="relative mt-2 lg:absolute bottom-0 mb-4">
                      <a class="inline bg-submitButton py-2 px-6 rounded-full  text-white hover:bg-adminLoginTextColor transition duration-300 ease-in-out" href="/customer/view-promo">View</a>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="w-full lg:w-1/3 md:mx-2 mb-4 md:mb-0">
                <div class="bg-white rounded-lg overflow-hidden shadow relative">
                  <img class="h-full w-full object-cover object-center" src="{{ asset('images/promo1.png') }}" alt="promo1">
                  <div class="p-4 h-auto md:h-40 lg:h-48">
                    <a href="#" class="block text-blue-500 hover:text-blue-600 font-semibold mb-2 text-lg md:text-base lg:text-lg">
                      Everything Opening Promo
                    </a>
                    <div class="text-gray-600 text-sm leading-relaxed block md:text-xs lg:text-sm">
                      Validity Period: January 10, 2021 - April 28, 2021
                    </div>
                    <div class="relative mt-2 lg:absolute bottom-0 mb-4">
                      <a class="inline bg-submitButton py-2 px-6 rounded-full  text-white hover:bg-adminLoginTextColor transition duration-300 ease-in-out" href="/customer/view-promo">View</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
      </div>
      {{-- Book --}}
			<div x-show="activeTab===3" class="text-black"> 
        <form action="/customer/reserve" method="POST">
          @csrf
        <div class=" grid xl:grid-cols-2 bg-gray-100">
        <div class="flex w-full items-center justify-center lg:mb-32 2xl:ml-24 ">
        <div class="grid w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">
            <div class="grid grid-cols-1">
                <label class="uppercase md:text-sm text-xs text-adminLoginTextColor text-light font-semibold  flex justify-start items-start">Date</label>
                <select name="reserveDate">
                  <option value=""></option>
                </select>
                <input class="py-2 px-3 rounded-lg border-2 border-purple-300 mt-1 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="date" name="reserveDate"/>
            </div>
            <div class="grid grid-cols-1">
                <label class="uppercase md:text-sm text-xs text-adminLoginTextColor text-light font-semibold  flex justify-start items-start">Time</label>
                <input class="py-2 px-3 rounded-lg border-2 border-purple-300 mt-1 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="time" name="reserveTime" />
            </div>
            </div>
            <div class="grid grid-cols-1 mt-5 mx-7">
                <label class="uppercase md:text-sm text-xs text-adminLoginTextColor text-light font-semibold flex justify-start items-start">Order Set</label>
                <select name="orderSet" class="py-2 px-3 rounded-lg border-2 border-purple-300 mt-1 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                  @foreach ($orderSets as $orderSet)
                    <option value="{{ $orderSet->id }}" class="">{{ $orderSet->orderSetName }}</option>
                  @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 mt-5 mx-7">
                <label class="uppercase md:text-sm text-xs text-adminLoginTextColor text-light font-semibold  flex justify-start items-start">No. of Persons</label>
                <input class="py-2 px-3 rounded-lg border-2 border-purple-300 mt-1 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="number" name="numberOfPersons" />
            </div>
            <div class="grid grid-cols-1 mt-5 mx-7">
                <label class="uppercase md:text-sm text-xs text-adminLoginTextColor text-light font-semibold  flex justify-start items-start">Estimated Hours of Stay</label>
                @if ($restaurant->rTimeLimit == 0)
                  <select name="hoursOfStay" class="py-2 px-3 rounded-lg border-2 border-purple-300 mt-1 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                    <option value="2">2 hours</option>
                    <option value="3">3 hours</option>
                    <option value="4">4 hours</option>
                    <option value="5">5 hours</option>
                  </select>
                @else 
                  <input class="py-2 px-3 rounded-lg border-2 border-purple-300 mt-1 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="text" value="{{ $restaurant->rTimeLimit }}" name="hoursOfStay" readonly/>
                @endif
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">
                <div class="grid grid-cols-1">
                <div class="flex flex-row gap-2">
                    <input class=" rounded-lg border-2 border-purple-300 mt-1 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="checkbox" name=""/>
                    <label class="uppercase md:text-sm text-xs text-adminLoginTextColor text-light font-semibold  flex justify-start items-start"> 7 Below</label>
                </div>
                    <input class="py-2 px-3 rounded-lg border-2 border-purple-300 mt-1 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="text" name="" />
                </div>
                <div class="grid grid-cols-1">
                <div class="flex flex-row gap-2">
                    <input class=" rounded-lg border-2 border-purple-300 mt-1 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="checkbox" name=""/>
                    <label class="uppercase md:text-sm text-xs text-adminLoginTextColor text-light font-semibold  flex justify-start items-start"> Senior Citizen/PWD</label> 
                </div>
                    <input class="py-2 px-3 rounded-lg border-2 border-purple-300 mt-1 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="text" name=""/>
                </div>
                    <div class="flex flex-row gap-2">
                        <input class=" rounded-lg border-2 border-purple-300 mt-1 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="checkbox" name=""/>
                        <label class="uppercase md:text-sm text-xs text-adminLoginTextColor text-light font-semibold flex justify-start items-start">Apply your Reward</label>
                    </div>
                 </div>
            </div>
            </div>
            <div>
                <div class="flex mx-auto items-center justify-center 2xl:mt-8 mb-4 max-w-lg ">
                <div class="w-full max-w-xl bg-white rounded-lg">
                    <div class="flex flex-wrap mb-6">
                        <h2 class="px-4 pt-5 md:text-sm text-xs text-adminLoginTextColor font-semibold text-light uppercase ">Notes:</h2>
                        <div class="w-full md:w-full px-3 mb-2 mt-2">
                            <textarea rows="15" cols="100" class=" rounded border border-gray-400 leading-normal resize-none w-full h-full py-2 px-3 font-medium placeholder-adminLoginTextColor focus:outline-none focus:bg-white" name="body" id="" placeholder='Type your message here...' required></textarea>
                        </div>
                        <div class="w-full md:w-full flex items-center justify-center px-3">
                            <div class="flex justify-center items-center">
                            <a href="/customer/confirm-reservation" class="bg-submitButton font-medium py-3 px-36 md:px-60 border rounded-lg hover:bg-adminLoginTextColor  text-white transition duration-300 ease-in-out mx-auto ">Next</a>
                            </div>
                        </div>
                    </div> 
                  </form>
    </div>
    </div>
		</div>
	</div>
	<!--actual component end-->


<script>
	function setup() {
    return {
      activeTab: 0,
      tabs: [
          "About",
          "Reviews",
          "Promos",
          "Book",
      ]
    };
  };
</script>

@endsection