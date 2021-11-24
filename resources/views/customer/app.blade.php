<!DOCTYPE html>
<html lang="en">
  <head>
   <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Molle:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <title>Samquicksal</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    <style>
      .gradient {
        background: linear-gradient(90deg, #D8345F 0%, #F2B4B4 100%);
      }
    </style>
  </head>
  <body class="leading-normal tracking-normal text-white gradient" style="font-family: 'Source Sans Pro', sans-serif;">
    <nav class="bg-black shadow-md relative" x-data="{navbarOpen:false}">
      <!-- container -->
      <div class="container flex flex-wrap px-4 py-2 mx-auto lg:space-x-4">
        <!-- brand -->
        <a class="text-sm font-bold leading-relaxed inline-block mr-4 whitespace-nowrap uppercase text-white" href="/"><img class="w-36 lg:w-40" src="{{ asset('images/samquicksalWithWords.png') }}" alt="samquicksalWithWords"></a>
        <!-- brand -->
        <!-- toggler btn -->
        <button class="inline-flex items-center justify-center w-10 h-10 ml-auto text-white border rounded-md outline-none lg:hidden focus:outline-none mt-2" @click="navbarOpen = !navbarOpen">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <!-- toggler btn -->
        <!-- menu -->
        <div class="w-full mt-2 lg:inline-flex lg:w-auto lg:my-2" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
          <ul class="flex flex-col w-full space-y-2 lg:w-auto lg:flex-row lg:space-y-0 lg:space-x-2 items-center">
            <li>
              <a href="/customer/customer-home" class="flex px-4 py-4 font-medium text-white rounded-md hover:bg-adminLoginTextColor">Home</a>
            </li>
            <li>
              <a href="/customer/restaurant-promo"class="flex px-4 py-4 font-medium text-white rounded-md hover:bg-adminLoginTextColor">Promos</a>
            </li>
            <li>
              <a href="/customer/view-restaurant" class="flex px-4 py-4 font-medium text-white  rounded-md hover:bg-adminLoginTextColor">Restaurant</a>
            </li>
            <li>
              <a href="#"class="flex px-4 py-4 font-medium text-white rounded-md hover:bg-adminLoginTextColor">Notification</a>
            </li>
            @if ($hasLiveStatus)
                <li>
                    <a href="/customer/livestatus">
                        <img class="w-8" src="{{ asset('images/circle-gif.gif') }}" alt="circle-gif">
                    </a>
                </li>
            @endif
          </ul>
        </div>
        <div class="bg-black flex justify-end items-end dark:bg-gray-500 ">
      <div x-data="{ open: false }" class="bg-black dark:bg-gray-800 w-64 shadow flex justify-end items-end right-44 absolute">
      <div @click="open = !open" class="relative border-b-4 border-transparent py-3" :class="{'border-indigo-700 transform transition duration-300 ': open}" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100">
        <div class="flex justify-center items-center space-x-3 cursor-pointer">
          <div class="w-12 h-12 rounded-full overflow-hidden border-2 dark:border-white border-gray-900">
              @if ($customer->profileImage != null)
                <img src="{{ asset('uploads/customerAccounts/logo/'.$customer->id.'/'.$customer->profileImage) }}" alt="user_logo" class="w-full h-full object-cover">
              @else
                <img src="{{ asset('images/user-default.png') }}" alt="user_logo" class="w-full h-full object-cover">
              @endif
          </div>
          <div class="font-semibold dark:text-white text-white text-lg">
            <div class="cursor-pointer">{{ $customer->name }}</div>
          </div>
        </div>
        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute w-60 px-5 py-3 dark:bg-gray-800 bg-white rounded-lg shadow border dark:border-transparent mt-5">
          <ul class="space-y-3 dark:text-white">
            <li class="font-medium">
              <a href="/customer/editprofile" class="flex items-center transform transition-colors duration-200 border-r-4 border-transparent hover:border-indigo-700 text-black">
                <div class="mr-3">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                Account
              </a>
            </li>
          
            <hr class="dark:border-gray-700">
            <li class="font-medium">
              <a href="/customer/logout" class="flex items-center transform transition-colors duration-200 border-r-4 border-transparent hover:border-red-600 text-black">
                <div class="mr-3 text-red-600">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </div>
                Logout
                  </a>
                </li>
              </ul>
            </div>
          </div>
      </div>
    </div>
    </div>
    </nav>

    {{-- FOR ENDORSEMENT --}}
    @if ($customer->deviceToken == null)
        <div class="w-full bg-white py-5">
            <p class=" text-black ml-48 font-black font-Roboto">Download <span class="text-submitButton">
                <a href="#">Samquicksal</a></span> App for better experience. 
            </p>
        </div>
    @endif

    @yield('content')

  </div>

  <script src="{{ asset('js/customernav.js') }}"></script>
  <script src="{{ asset('js/customer.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script>
      function toggleNavbar(collapseID) {
        document.getElementById(collapseID).classList.toggle("hidden");
        document.getElementById(collapseID).classList.toggle("block");
      }
    </script>
  </body>
</html>
