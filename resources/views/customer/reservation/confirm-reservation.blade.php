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
    <title>Reserve | Samquicksal</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
</head>
<body class="antialiased font-Roboto ">


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

    {{-- Header --}}
    <header>
    <div class="w-full bg-center bg-cover h-80" style="background-image: url({{asset('images/samgyupGrill.png')}});">
    
        <div class="container flex items-center justify-start w-full h-full">
            <div class="md:ml-32 flex flex-col md:flex-row text-white mx-auto md:mx-0 ">
               <img class="w-32 xl:w-52 rounded-full mx-auto" src="{{ asset('images/romBab2.png') }}" alt="romBab2">
               <div class="flex flex-col items-center md:items-start md:mt-20 pl-5">
               <h1 class="text-3xl font-bold">Golden Baboy</h1>
               <p class="text-base ">Morayta, Metro Manila</p>
               </div>
            </div>
        </div>
    </div>
    </header>
    
    {{-- main --}}
    <div class="mx-auto flex justify-center items-center flex-col">
      <h1 class="font-black text-xl md:text-4xl font-Montserrat mx-auto my-10">Confirm Reservation Details</h1>
      <div class="bg-manageFoodItemHeaderBgColor py-10 px-10">
          <div class="mx-auto bg-adminViewAccountHeaderColor2 grid grid-cols-8 p-5 ">

            <div class="col-span-7">
              <p class="font-Montserrat text-gray-400">Date:</p>
            </div>
            <div class="col-span-1">
              <p class=" font-Roboto">May 18,2021</p>
            </div>
            <div class="col-span-7">
              <p class="font-Montserrat text-gray-400">Time:</p>
            </div>
            <div class="col-span-1">
              <p class=" font-Roboto">10:00 AM</p>
            </div>
            <div class="col-span-7">
              <p class="font-Montserrat text-gray-400">Order:</p>
            </div>
            <div class="col-span-1">
              <p class=" font-Roboto">399</p>
            </div>
            <div class="col-span-7">
              <p class="font-Montserrat text-gray-400">No. of Persons:</p>
            </div>
            <div class="col-span-1">
              <p class=" font-Roboto">10</p>
            </div>
            <div class="col-span-7">
              <p class="font-Montserrat text-gray-400">Estimated Hours of Stay:</p>
            </div>
            <div class="col-span-1">
              <p class=" font-Roboto">2</p>
            </div>
            <div class="col-span-7">
              <p class="font-Montserrat text-gray-400">Children (7 yrs old below):</p>
            </div>
            <div class="col-span-1">
              <p class=" font-Roboto">2</p>
            </div>
            <div class="col-span-7">
              <p class="font-Montserrat text-gray-400">Senior/PWD:</p>
            </div>
            <div class="col-span-1">
              <p class=" font-Roboto">2</p>
            </div>

           
          </div>
          <div>
            <h1 class="font-Montserrat my-2 text-gray-400">Estimated Price:</h1>
            <div class="grid grid-cols-10">
              <div class="col-span-4"><p>10x</p></div>
              <div class="col-span-4"><p>Order Set 399</p></div>
              <div class="col-span-1"><p>3990</p></div>
              <div class=""><p>Php</p></div>
            </div>
            <hr class="mt-3">
          </div>
          <div class="grid grid-cols-10">
              <div class="col-span-8 text-gray-400"><p>Subtotal:</p></div>
              <div class="col-span-1 text-submitButton">3990.00</div>
              <div class="">Php</div>
              <div class="col-span-8"><p>Service Fee</p></div>
              <div class="col-span-1">0</div>
              <div class="">Php</div>
              <div class="col-span-8"><p>Incl. Tax</p></div>
              <div class="col-span-1">0</div>
              <div class="">Php</div>
          </div>
          <hr class="mt-3">
            <h1 class="font-Montserrat my-2 text-gray-400">Discount:</h1>
          <div class="grid grid-cols-10">
            <div class="col-span-4"><p>Senior Citizen</p></div>
            <div class="col-span-4"><p>5%</p></div>
            <div class="col-span-1"><p>-200</p></div>
            <div class=""><p>Php</p></div>
          </div>
          <div class="grid grid-cols-10">
            <div class="col-span-4"><p>Children</p></div>
            <div class="col-span-4"><p>2 Free</p></div>
            <div class="col-span-1"><p>-798</p></div>
            <div class=""><p>Php</p></div>
          </div>
          <hr class="mt-3">
          <div class="grid grid-cols-10">
            <div class="col-span-8"><p class="font-Montserrat text-gray-400">Total Price:</p></div>
            <div class="col-span-1 text-manageRestaurantSidebarColorActive">2992.00</div>
            <div class="">Php</div>
          </div>
          
          <p class="text-darkerSubmitButton font-bold mt-5 text-Roboto mx-auto flex justify-center items-center">
            <i class="fas fa-info-circle mb-6 mr-2 text-xs"></i>Discount will only apply upon showing of PWD/Senior Citizen Card and for Kids 7 <br>and below, must bring birth cert or id with birth<br></p>
          
      </div>

      <div class="flex flex-col md:flex-row my-10 mx-10">
        <a href="/customer/reservation" class="my-2 px-28 py-2 border-2 border-submitButton bg-white text-submitButton mx-2 hover:text-white hover:bg-adminLoginTextColor transition duration-300 ease-in-out">Go Back</a>
        <a href="#" class="my-2 px-20 py-2 border-2 mx-1 bg-submitButton text-white hover:text-white hover:bg-adminLoginTextColor transition duration-300 ease-in-out">Confirm Reservation</a>
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