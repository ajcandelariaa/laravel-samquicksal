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
        background: linear-gradient(90deg, #363636 0%, #363636 100%);
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
          <ul class="flex flex-col w-full space-y-2 lg:w-auto lg:flex-row lg:space-y-0 lg:space-x-2">
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
          </ul>
        </div>
        <div class="bg-black flex justify-end items-end dark:bg-gray-500 ">
      <div x-data="{ open: false }" class="bg-black dark:bg-gray-800 w-64 shadow flex justify-end items-end right-44 absolute">
      <div @click="open = !open" class="relative border-b-4 border-transparent py-3" :class="{'border-indigo-700 transform transition duration-300 ': open}" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100">
        <div class="flex justify-center items-center space-x-3 cursor-pointer">
          <div class="w-12 h-12 rounded-full overflow-hidden border-2 dark:border-white border-gray-900">
            <img src="https://images.unsplash.com/photo-1610397095767-84a5b4736cbd?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80" alt="" class="w-full h-full object-cover">
          </div>
          <div class="font-semibold dark:text-white text-white text-lg">
            <div class="cursor-pointer">Customer Name</div>
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
              <a href="#" class="flex items-center transform transition-colors duration-200 border-r-4 border-transparent hover:border-red-600 text-black">
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
  
    <div class="pt-0">
      <div class="container px-3 mx-auto flex-wrap flex-col md:flex-row items-center grid grid-cols-2">
        <div class="w-10/12 mt-28 ml-auto relative">
          <img class="w-7/12 relative z-0 " src="{{ asset('images/circle-pink.gif') }}" alt="circle-pink">
            <div class="absolute top-24 xl:left-28 z-10">
                <h1 class="text-xl ">Cancellation Time</h1>
                <h1 class="text-9xl">15</h1>
            </div>
            <h1 class="text-2xl left-36 top-64 absolute">minutes</h1>
        </div>
        <div class="mx-auto mt-28">
            <h1 class="font-Montserrat text-2xl">Cancellation is void when time exceeds 15 minutes</h1>
            <div class="my-10 flex flex-row justify-center items-center mx-auto font-Montserrat">
                <a class="bg-submitButton py-2 px-20 mr-5 transition duration-300 ease-in-out border-2 hover:bg-adminLoginTextColor" href="#">Go back</a>
                <a class="bg-white py-2 px-16 border-2 border-submitButton hover:bg-adminLoginTextColor text-submitButton hover:text-white transition duration-300 ease-in-out" href="#">Cancel Booking</a>
            </div>
        </div>
      </div>
    </div>
    <div class="relative">
      <svg viewBox="0 0 1428 174" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g transform="translate(-2.000000, 44.000000)" fill="#FFE2E2" fill-rule="nonzero">
            <path d="M0,0 C90.7283404,0.927527913 147.912752,27.187927 291.910178,59.9119003 C387.908462,81.7278826 543.605069,89.334785 759,82.7326078 C469.336065,156.254352 216.336065,153.6679 0,74.9732496" opacity="0.100000001"></path>
            <path
              d="M100,104.708498 C277.413333,72.2345949 426.147877,52.5246657 546.203633,45.5787101 C666.259389,38.6327546 810.524845,41.7979068 979,55.0741668 C931.069965,56.122511 810.303266,74.8455141 616.699903,111.243176 C423.096539,147.640838 250.863238,145.462612 100,104.708498 Z"
              opacity="0.100000001"
            ></path>
            <path d="M1046,51.6521276 C1130.83045,29.328812 1279.08318,17.607883 1439,40.1656806 L1439,120 C1271.17211,77.9435312 1140.17211,55.1609071 1046,51.6521276 Z" id="Path-4" opacity="0.200000003"></path>
          </g>
          <g transform="translate(-4.000000, 76.000000)" fill="#FFE2E2" fill-rule="nonzero">
            <path
              d="M0.457,34.035 C57.086,53.198 98.208,65.809 123.822,71.865 C181.454,85.495 234.295,90.29 272.033,93.459 C311.355,96.759 396.635,95.801 461.025,91.663 C486.76,90.01 518.727,86.372 556.926,80.752 C595.747,74.596 622.372,70.008 636.799,66.991 C663.913,61.324 712.501,49.503 727.605,46.128 C780.47,34.317 818.839,22.532 856.324,15.904 C922.689,4.169 955.676,2.522 1011.185,0.432 C1060.705,1.477 1097.39,3.129 1121.236,5.387 C1161.703,9.219 1208.621,17.821 1235.4,22.304 C1285.855,30.748 1354.351,47.432 1440.886,72.354 L1441.191,104.352 L1.121,104.031 L0.457,34.035 Z"
            ></path>
          </g>
        </g>
      </svg>
    </div>
    <div class="bg-tableBgHeader flex justify-center items-center mx-auto">
        <div class="flex flex-col sm:flex-row justify-center items-center ">
            <div class="flex flex-col md:flex-row justify-center items-center mx-32">
                <img class=" w-3/12 xl:w-4/12 ml-28" src="{{ asset('images/homepage.png') }}" alt="homepage">
                <div class="flex flex-col justify-center mx-auto items-center">
                <h1 class="text-black font-Roboto font-bold text-3xl">Use the app now for better experience</h1>
                <img class="w-7/12" src="{{ asset('images/samquicksalWithWords.png') }}" alt="samquicksalWithWords">
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script>
      function toggleNavbar(collapseID) {
        document.getElementById(collapseID).classList.toggle("hidden");
        document.getElementById(collapseID).classList.toggle("block");
      }
    </script>
  </body>
</html>
