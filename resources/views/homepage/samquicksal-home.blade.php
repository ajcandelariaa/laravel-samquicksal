<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Molle:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css2') }}">
    <link rel="stylesheet" href="{{ asset('css/main.scss') }}">
    <title>Samquicksal</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    
    <script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body class="antialiased">
    @if (session()->has('passwordUpdated'))
        <script>
            Swal.fire(
                'Your password has been updated',
                'You can now login again',
                'success'
            );  
        </script>
    @endif
    <div class="fullpage ">
      <header class="top-0 z-50 w-full flex flex-wrap items-center justify-between px-2 header_1">
      <div class="container px-4 mx-auto flex flex-wrap items-center justify-between">
        <div class="w-full relative flex justify-between lg:w-auto lg:static lg:block lg:justify-start">
          <a class="text-sm font-bold leading-relaxed inline-block mr-4 py-2 whitespace-nowrap uppercase text-white" href="/"><img class="w-36 xl:w-36" src="{{ asset('images/samquicksalWithWords.png') }}" alt="samquicksalWithWords"></a>
    
          <button class="cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none" type="button" onclick="toggleNavbar('example-collapse-navbar')">
            <i class="text-submitButton fas fa-bars "></i>
          </button>
        </div>
        <nav class="lg:flex flex-grow items-center bg-white lg:bg-transparent lg:shadow-none hidden" id="example-collapse-navbar">
          <ul class="flex flex-col lg:flex-row list-none lg:ml-auto font-Montserrat">
            <li class="flex items-center">
              <a class="nav__link cursor-pointer text-darkerSubmitButton  px-7 py-4 lg:py-2 flex items-center text-xs uppercase font-bold" href="/restaurant" target="_blank">
              <p class="md:hidden sm:hidden hidden xl:contents">Be Our Partner</p>
                  <span class="xl:hidden inline-block ml-2">Be Our Partner</span><a>
            </li>
            <li class="flex items-center">
              <a class=" nav__link cursor-pointer text-darkerSubmitButton px-7 py-4 lg:py-2 flex items-center text-xs uppercase font-bold">
                <p class="md:hidden sm:hidden hidden    xl:contents">Login</p>
              <span class="xl:hidden inline-block ml-2 ">Login</span></a>
            </li>
            <li class="flex items-center">
              <a class="nav__link cursor-pointer text-darkerSubmitButton  px-7 py-4 lg:py-2 flex items-center text-xs uppercase font-bold">
                <p class="md:hidden sm:hidden hidden xl:contents">Signup</p>
                <span class="xl:hidden inline-block ml-2">Signup</span></a>
            </li>
          </ul>
        </nav>
      </div>
    </header>
      <main>
        <div class="home-intro relative pt-16 pb-32 flex content-center items-center justify-center" style="min-height: 75vh;">
          <div class="absolute top-0 w-full h-full bg-center bg-cover" style='background-image: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0)), url({{asset('images/restaurant1.png')}}); background-repeat: no-repeat; background-size: cover;" class="w-full h-full'>
            <span id="blackOverlay" class="w-full h-full absolute opacity-75 bg-black"></span>
          </div>
          <div class="container relative mx-auto">
            <div class="items-center flex flex-wrap">
              <div class="w-full xl:w-6/12 px-4 ml-auto mr-auto text-center">
                  <h1 class="xl:text-5xl lg:text-4xl md:text-2xl text-xl font-Montserrat font-bold text-white">
                    "The Quick way to Samgyup"
                  </h1>
                  <p class="my-1 lg:mt-5 md:mb-6 lg:mb-10 mb-4 lg:ml-1 text-xs lg:text-2xl font-medium text-white font-Montserrat">
                    Treat Yourself using Samquicksal!
                  </p>
                        <div class="grid gap-8 items-start justify-center ">
                          <div class="relative group">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-submitButton to-purple-600 rounded-lg filter blur-xl opacity-75 group-hover:opacity-100 transition duration-500 group-hover:duration-200"></div>
                          <a class="relative px-5 py-5 bg-black rounded-lg leading-none flex items-center" href="/customer" target="_blank">
                            <span class="text-gray-100 pr-1 text-sm sm:text-sm md:text-md lg:text-lg xl:text-lg font-bold ">Dine in</span>
                            <span class="text-submitButton font-bold group-hover:text-gray-100 transition duration-200 text-sm sm:text-sm md:text-md lg:text-lg xl:text-lg">Now &rarr;</span>
                          </a>
                      </div>
                    </div>
              </div>
            </div>
          </div>
          <div
            class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden"
            style="height: 70px;">
            <svg
              class="absolute bottom-0 overflow-hidden"
              xmlns="http://www.w3.org/2000/svg"
              preserveAspectRatio="none"
              version="1.1"
              viewBox="0 0 2560 100"
              x="0"
              y="0">
              <polygon class="text-gray-200 fill-current" points="2560 0 2560 100 0 100">
              </polygon>
            </svg>
          </div>
        </div>
    
        <section class="pb-20 bg-gray-200 -mt-24">
          <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
              <div class="lg:pt-12 pt-6 w-full md:w-4/12 px-4 text-center">
                <div
                  class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg">
                  <div class="px-4 py-5 flex-auto">
                    <div
                      class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-red-400">
                    <i class="fas fa-hourglass-half"></i>
                    </div>
                    <h6 class="text-xl font-semibold">Long wait times </h6>
                    <p class="mt-2 mb-4 text-gray-600">
                      customer experience long wait times which lead to wasted time and revenues lost for the restaurant
                    </p>
                  </div>
                </div>
              </div>
              <div class="w-full md:w-4/12 px-4 text-center">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg">
                  <div class="px-4 py-5 flex-auto">
                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-blue-400">
                      <i class="fas fa-hand-point-up"></i>
                    </div>
                    <h6 class="text-xl font-semibold">Restaurant Services at a tap</h6>
                    <p class="mt-2 mb-4 text-gray-600">
                      Shy to raise that hand and ask for help?, Now you don't have to! just use our app request for water grill change, side dish or that extra plate of meat
                    </p>
                  </div>
                </div>
              </div>
              <div class="pt-6 w-full md:w-4/12 px-4 text-center">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg">
                  <div class="px-4 py-5 flex-auto">
                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-green-400">
                      <i class="fas fa-search-location"></i>
                    </div>
                    <h6 class="text-xl font-semibold">Can't find the right samgyeop restaurant?</h6>
                    <p class="mt-2 mb-4 text-gray-600">
                      Finding the right restaurant with good price and appropriate tables can be a challenge, with our app we have done that for you.
    
                    </p>
                  </div>
                </div>
              </div>
            </div>
        <div class=" font-Montserrat flex flex-wrap items-center text-darkerSubmitButton mt-24 sm:mt-24 md:mt-0 lg:mt-0 xl:mt-0 ">
            <div class=" md:w-5/12 px-4 mr-auto ml-auto mb-4 lg:mb-0 relative">
              <div class="col fade-in absolute top-0 md:-left-4 lg:-left-4 xl:-left-4 md:w-36 md:h-36 lg:w-48 lg:h-48 xl:w-72 xl:h-72 bg-purple-300 rounded-full mix-blend-multiply  filter blur-xl opacity-70 animate-blob  ">
              </div>
              <div class="absolute top-0 md:right-16 lg:right-16 xl:right-24 md:w-36 md:h-36 lg:w-48 lg:h-48 xl:w-72 xl:h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000 col fade-in">
              </div>
              <div class="absolute md:-bottom-12 lg:-bottom-20 xl:-bottom-40 md:left-12 lg:left-16 xl:left-24 md:w-36 md:h-36 lg:w-48 lg:h-48 xl:w-72 xl:h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000 col fade-in">
              </div>
              <div class="relative col fade-in">
                <div class="text-gray-600 p-3 text-center inline-flex items-center justify-center w-16 h-16 mb-6 shadow-lg rounded-full bg-gray-100">
                  <i class="fas fa-smile-wink text-2xl"></i>
              </div>
                <h3 class="sm:text-lg md:text-xl lg:text-2xl xl:text-4xl mb-2 font-bold font-Montserrat leading-normal ">
                  A full filling samgyeopsal experience starts here
                </h3>
                <li class="text-sm md:text-base lg:text-lg xl:text-xl xl:text-start">Shorter wait times</li>
                <li class="text-sm md:text-base lg:text-lg xl:text-xl ">Call the waiters attention in app</li>
                <li class="text-sm md:text-base lg:text-lg xl:text-xl">Loyalty rewards and Promos</li>
              </div>
            </div>
    
            {{-- sample --}}
            <div class=" sm:w-8/12 md:w-5/12 lg:w-4/12 xl:w-5/12 px-4 ml-auto mr-auto col fade-in ">
              <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-darkerSubmitButton">
                <img src="{{ asset('images/restaurant1.png') }}" alt="restaurant1" class="sm:w-full md:w-full lg:w-full xl:w-full align-middle rounded-t-lg"/>
                <blockquote class="relative p-4 sm:p-5 md:p-3 lg:p-4 xl:p-8 lg:mb-0 xl:mb-4">
                  <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 583 95" class="absolute left-0 w-full block" style="height: 95px; top: -94px;">
                    <polygon points="-30,95 583,95 583,65" class="text-darkerSubmitButton fill-current"></polygon>
                  </svg>
                  <h4 class="text-base md:text-base lg:text-lg xl:text-xl font-bold text-white">
                    What we aim?
                  </h4>
                  <p class="text-xs sm:text-sm md:text-xs lg:text-sm xl:text-base font-light mt-2 text-white flex-shrink-0 text-justify">
                    We aim to provide a quicker, easier, and safer dining and serving experience for both the management and their customers.
                  </p>
                </blockquote>
              </div>
            </div>
          </div>
        </div>
      </section>
    
        <section class="font-Montserrat relative">
          <div class="bottom-auto top-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden -mt-20" style="height: 80px;">
              <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
                <polygon class="text-white fill-current"points="2560 0 2560 100 0 100">
              </polygon>
              </svg>
            </div>
            <div class="container flex flex-col items-start px-5 py-16 lg:pt-44 mx-auto lg:items-center md:flex-row xl:px-28 lg:px-28 ">
              <div class="mx-auto xl:mb-10 xl:mt-36 xl:w-5/6 xl:max-w-lg xl:ml-24 lg:mt-52 lg:mr-3 lg:w-6/12 md:w-5/12 md:mt-48 md:mr-2 sm:w-7/12 w-9/12 mb-5 flex-shrink shadow-xl col fade-in">
                <div class="bg-submitButton text-white rounded-xl">
                    <div class="flex justify-center">
                        <h1 class="uppercase font-black md:text-4xl lg:text-5xl xl:text-7xl text-4xl lg:ml-11 ml-3 mr-2 my-4  col fade-in">no</h1>
                    <div class="flex flex-col mr-2 my-4 text-sm lg:text-base xl:text-3xl md:text-sm  col fade-in ">
                        <p>Long Wait Hours!</p>
                        <p>More Falling in Line!</p>
                    </div>
                </div>
                <div class="flex justify-center items-center">
                    <img class=" w-8/12 sm:w-7/12 lg:w-7/12 xl:w-7/12 md:w-7/12 shadow-2xl rounded-t-xl col fade-in" src="{{ asset('images/muhanUnilimitedSamgyupsal.png') }}" alt="muhanUnilimitedSamgyupsal">
                </div>
              </div>
              </div>
              <div class="shadow-xl mx-auto mb-24 xl:-mt-20 xl:mr-28 xl:w-5/6 xl:max-w-lg md:w-5/12 md:ml-2 lg:ml-3 lg:-mt-10 lg:w-6/12 sm:w-7/12 w-9/12 col fade-in ">
                <div class="bg-boxPeach text-submitButton rounded-xl relative pb-48 sm:pb-56 md:pb-60 lg:pb-72 xl:pb-96  shadow-xl">
                    <div class="flex flex-col py-5 px-5 xl:py-5 xl:px-7 xl:text-3xl lg:text-2xl md:text-2xl sm:text-2xl text-xl font-bold col fade-in">
                        <p>Earn Rewards for</p>
                        <p>Completing</p>
                        <p>Our Loyalty Card</p>
                    </div>
                <div class="flex justify-center items-center absolute col fade-in ">
                    <img class="flex-shrink w-full sm:w-full md:w-full lg:w-full xl:w-full rounded-t-xl" src="{{ asset('images/rewards.png') }}" alt="rewards">
                </div>
          </div>
          </div>
          </div>
        </section>
    
          <div class="bg-gradient-to-b from-white to-headerBgColor">
          <section class=" font-Montserrat mt-20 sm:mt-28 md:mt-16 lg:mt-20 xl:mt-60">
              <div class="container flex flex-col items-center lg:px-5 xl:px-5 lg:py-7 xl:py-8 mx-auto">
                <div class="flex flex-col xl:w-full text-left ">
                  <div class="w-full mx-auto xl:w-1/2">
                    <h1 class="mx-auto mb-16 sm:mb-12 md:mb-16 lg:mb-20 xl:mb-16 text-2xl sm:text-3xl md:text-3xl lg:text-4xl xl:text-5xl leading-none flex justify-center items-center font-bold text-adminLoginTextColor col fade-in">More Benefits to Enjoy </h1>
                  </div>
                </div>
                <div class="flex flex-row lg:flex-row md:flex-row xl:flex-row sm:flex-row items-center mx-auto mb-3 sm:mb-4 md:mb-5 lg:mb-6 xl:mb-7 xl:w-1/2 lg:w-8/12 md:w-8/12 sm:w-10/12 col fade-in">
                  <div class="inline-flex items-center justify-center flex-shrink-0 w-4/12 sm:w-4/12 md:w-3/12 lg:w-3/12 xl:w-3/12 sm:mr-10">
                      <div class="inline-flex items-center justify-center flex-shrink-0 sm:mr-10 xl:w-11/12 lg:w-10/12 md:w-11/12 sm:w-7/12 w-7/12 rounded-l-xl sm:rounded-l-xl md:rounded-l-xl lg:rounded-l-xl xl:rounded-l-xl bg-adminViewAccountHeaderColor sm:bg-adminViewAccountHeaderColor md:bg-adminViewAccountHeaderColor lg:bg-adminViewAccountHeaderColor xl:bg-adminViewAccountHeaderColor h-20 sm:h-28 md:h-32 lg:h-32 ">
                          <img class="w-8/12 sm:w-9/12 md:w-8/12 lg:w-8/12 xl:w-7/12 xl:mr-8 xl:-ml-4 lg:mr-5 md:mr-5 md:ml-3 xl:my-4 lg:my-4 md:my-4" src="{{ asset('images/waiting.png') }}" alt="waiting">
                      </div>
                  </div>
                  <div class="flex-grow xl:-ml-28 lg:-ml-20 md:-ml-20 sm:-ml-24 -ml-7 xl:mr-16 lg:mr-10 md:mr-10 sm:mr-10 mr-5 xl:pl-8 lg:pl-7 md:pl-7 sm:pl-7 xl:h-32 lg:h-32 md:h-32 sm:h-28 h-20 rounded-r-xl bg-gradient-to-r from-gray-100 to-fadedPink bg-sm:mt-0 px-2 ">
                    <h2 class="mb-1 lg:mt-2 md:mt-4 sm:mt-2 mt-2 lg:mb-2 md:mb-2 sm:mb-1 lg:text-xl xl:text-xl text-xs font-bold  text-adminLoginTextColor"> Get Notified</h2>
                    <p class="text-xxs md:text-xs lg:text-base xl:text-base leading-tight sm:leading-relaxed md:leading-relaxed lg:leading-relaxed xl:leading-relaxed  sm:px-0 md:px-0 lg:px-0 xl:px-0 text-submitButton tracking-tighter">Reserve or queue in seconds, Choose the restaurant fill in the form and you're ready to go! </p>
                  </div>
                </div>
                <div class="flex flex-row lg:flex-row md:flex-row xl:flex-row sm:flex-row items-center mx-auto mb-3 sm:mb-4 md:mb-5 lg:mb-6 xl:mb-7 xl:w-1/2 lg:w-8/12 md:w-8/12 sm:w-10/12 col fade-in ">
                  <div class="inline-flex items-center justify-center flex-shrink-0 w-4/12 sm:w-4/12 md:w-3/12 lg:w-3/12 xl:w-3/12 sm:mr-10 ">
                      <div class="inline-flex items-center justify-center flex-shrink-0 sm:mr-10 xl:w-11/12 lg:w-10/12 md:w-11/12 sm:w-7/12 w-7/12 rounded-l-xl sm:rounded-l-xl md:rounded-l-xl lg:rounded-l-xl xl:rounded-l-xl bg-adminViewAccountHeaderColor sm:bg-adminViewAccountHeaderColor md:bg-adminViewAccountHeaderColor lg:bg-adminViewAccountHeaderColor xl:bg-adminViewAccountHeaderColor h-20 sm:h-28 md:h-32 lg:h-32">
                          <img class="w-8/12 sm:w-9/12 md:w-8/12 lg:w-8/12 xl:w-7/12 xl:mr-8 xl:-ml-4 lg:mr-5 md:mr-5 md:ml-3 xl:my-4 lg:my-4 md:my-4" src="{{ asset('images/bell.png') }}" alt="bell">
                      </div>
                  </div>
                  <div class="flex-grow xl:-ml-28 lg:-ml-20 md:-ml-20 sm:-ml-24 -ml-7 xl:mr-16 lg:mr-10 md:mr-10 sm:mr-10 mr-5 xl:pl-8 lg:pl-7 md:pl-7 sm:pl-7 xl:pr-3 lg:pr-3 md:pr-3 sm:pr-3 pr-2 xl:h-32 lg:h-32 md:h-32 sm:h-28 h-20 rounded-r-xl bg-gradient-to-r from-gray-100 to-fadedPink bg-sm:mt-0 px-2">
                    <h2 class="mb-1 lg:mt-2 md:mt-4 sm:mt-2 mt-2 lg:mb-2 md:mb-2 sm:mb-1 lg:text-xl xl:text-xl text-xs font-bold  text-adminLoginTextColor">No Long Wait Hours!</h2>
                    <p class="text-xxs md:text-xs lg:text-base xl:text-base leading-tight sm:leading-relaxed md:leading-relaxed lg:leading-relaxed xl:leading-relaxed  sm:px-0 md:px-0 lg:px-0 xl:px-0 text-submitButton tracking-tighter">No more guessing, you’ll know when your table is ready
                      when you get a notification from our app!</p>
                  </div>
                </div>
                <div class="flex flex-row lg:flex-row md:flex-row xl:flex-row sm:flex-row items-center mx-auto mb-3 sm:mb-4 md:mb-5 lg:mb-6 xl:mb-7 xl:w-1/2 lg:w-8/12 md:w-8/12 sm:w-10/12 col fade-in">
                  <div class="inline-flex items-center justify-center flex-shrink-0 w-4/12 sm:w-4/12 md:w-3/12 lg:w-3/12 xl:w-3/12 sm:mr-10 ">
                      <div class="inline-flex items-center justify-center flex-shrink-0 sm:mr-10 xl:w-11/12 lg:w-10/12 md:w-11/12 sm:w-7/12 w-7/12 rounded-l-xl sm:rounded-l-xl md:rounded-l-xl lg:rounded-l-xl xl:rounded-l-xl bg-adminViewAccountHeaderColor sm:bg-adminViewAccountHeaderColor md:bg-adminViewAccountHeaderColor lg:bg-adminViewAccountHeaderColor xl:bg-adminViewAccountHeaderColor h-20 sm:h-28 md:h-32 lg:h-32">
                          <img class="w-8/12 sm:w-9/12 md:w-8/12 lg:w-8/12 xl:w-7/12 xl:mr-8 xl:-ml-4 lg:mr-5 md:mr-5 md:ml-3 xl:my-4 lg:my-4 md:my-4" src="{{ asset('images/check.png') }}" alt="check">
                      </div>
                  </div>
                  <div class="flex-grow xl:-ml-28 lg:-ml-20 md:-ml-20 sm:-ml-24 -ml-7 xl:mr-16 lg:mr-10 md:mr-10 sm:mr-10 mr-5 xl:pl-8 lg:pl-7 md:pl-7 sm:pl-7 xl:pr-3 lg:pr-3 md:pr-3 sm:pr-3 pr-2 xl:h-32 lg:h-32 md:h-32 sm:h-28 h-20 rounded-r-xl bg-gradient-to-r from-gray-100 to-fadedPink bg-sm:mt-0 px-2">
                    <h2 class="mb-1 lg:mt-2 md:mt-4 sm:mt-2 mt-2 lg:mb-2 md:mb-2 sm:mb-1 lg:text-xl xl:text-xl text-xs font-bold  text-adminLoginTextColor">Be Rewarded</h2>
                    <p class="text-xxs md:text-xs lg:text-base xl:text-base leading-tight sm:leading-relaxed md:leading-relaxed lg:leading-relaxed xl:leading-relaxed sm:px-0 md:px-0 lg:px-0 xl:px-0 text-submitButton tracking-tighter">Stamp card for users that can be used for exclusive discounts and many more </p>
                  </div>
                </div>
                <div class="flex flex-row lg:flex-row md:flex-row xl:flex-row sm:flex-row items-center mx-auto mb-3 sm:mb-4 md:mb-5 lg:mb-6 xl:mb-7 xl:w-1/2 lg:w-8/12 md:w-8/12 sm:w-10/12 col fade-in ">
                  <div class="inline-flex items-center justify-center flex-shrink-0 w-4/12 sm:w-4/12 md:w-3/12 lg:w-3/12 xl:w-3/12 sm:mr-10 ">
                      <div class="inline-flex items-center justify-center flex-shrink-0 sm:mr-10 xl:w-11/12 lg:w-10/12 md:w-11/12 sm:w-7/12 w-7/12 rounded-l-xl sm:rounded-l-xl md:rounded-l-xl lg:rounded-l-xl xl:rounded-l-xl bg-adminViewAccountHeaderColor sm:bg-adminViewAccountHeaderColor md:bg-adminViewAccountHeaderColor lg:bg-adminViewAccountHeaderColor xl:bg-adminViewAccountHeaderColor h-20 sm:h-28 md:h-32 lg:h-32">
                          <img class="w-8/12 sm:w-9/12 md:w-8/12 lg:w-8/12 xl:w-7/12 xl:mr-8 xl:-ml-4 lg:mr-5 md:mr-5 md:ml-3 xl:my-4 lg:my-4 md:my-4" src="{{ asset('images/smile.png') }}" alt="smile">
                      </div>
                  </div>
                  <div class="flex-grow xl:-ml-28 lg:-ml-20 md:-ml-20 sm:-ml-24 -ml-7 xl:mr-16 lg:mr-10 md:mr-10 sm:mr-10 mr-5 xl:pl-8 lg:pl-7 md:pl-7 sm:pl-7 xl:pr-3 lg:pr-3 md:pr-3 sm:pr-3 pr-2 xl:h-32 lg:h-32 md:h-32 sm:h-28 h-20 rounded-r-xl bg-gradient-to-r from-gray-100 to-fadedPink bg-sm:mt-0 px-2 ">
                    <h2 class="mb-1 lg:mt-2 md:mt-4 sm:mt-2 mt-2 lg:mb-2 md:mb-2 sm:mb-1 lg:text-xl xl:text-xl text-xs font-bold  text-adminLoginTextColor">Exciting Promos</h2>
                    <p class="text-xxs md:text-xs lg:text-base xl:text-base leading-tight sm:leading-relaxed md:leading-relaxed lg:leading-relaxed xl:leading-relaxed sm:px-0 md:px-0 lg:px-0 xl:px-0 text-submitButton tracking-tighter">Be the first to hear about the exclusive promotions that our registered restaurants have to offer such as special lunch rates, holiday discounts, or exclusive promos for their anniversaries. </p>
                  </div>
                </div>
              </div>
            </section>
    
            <section>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 p-4 mt-10 lg:mt-32 pb-20  ">
                    <div class="flex justify-center items-center col fade-in">
                        <img class="w-32 md:w-56 lg:w-72 xl:w-72 shadow-2xl rounded-3xl" src="{{ asset('images/homepage.png') }}" alt="homepage">
                    </div>
                    <div class="font-Montserrat flex justify-center items-center flex-col ">
                        <h1 class="my-5 lg:mb-4 lg:text-4xl text-lg text-center font-bold col fade-in">Download The Samquicksal App Now!</h1>
                        <a href="#"><img class="w-5/12 sm:w-5/12 md:w-6/12 lg:w-7/12 xl:w-8/12  mx-auto transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110" src="{{ asset('images/googlePlay.png') }}" alt="googlePlay"></a>
                    </div>
                </div>
            </section>
            </div>
        <footer class=" transition duration-500 ease-in-out transform bg-adminLoginTextColor ">
            <div class="flex flex-col flex-wrap justify-center p-2 lg:p-5 md:flex-row">
                <nav class="flex flex-wrap items-center justify-center w-full mx-auto mb-1 lg:mb-6 text-base nprd">
                    <a href="#" class="px-1 lg:px-4 py-1 mr-1 text-xs lg:text-sm text-gray-50 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-submitButton ">About</a>
                    <a href="#" class="px-1 lg:px-4 py-1 mr-1 text-xs lg:text-sm text-gray-50 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-submitButton ">Contact</a>
                    <a href="#" class="px-1 lg:px-4 py-1 mr-1 text-xs lg:text-sm text-gray-50 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-submitButton ">Member</a>
                </nav>
                <span class="inline-flex justify-center w-full mx-auto -mb-5 lg:mt-0 mr-2 sm:ml-auto sm:mt-0">
                    <a class="text-white hover:text-submitButton transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2">
                        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="lg:w-5 w-3 lg:h-6 h-4" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                        </svg>
                    </a>
    
                    <a class="ml-3 text-white hover:text-submitButton transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2">
                        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="lg:w-5 w-3 lg:h-6 h-4" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                        </svg>
                    </a>
    
                    <a class="ml-3 text-white hover:text-submitButton transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="xl:w-5 w-3 xl:h-6 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                          </svg>
                    </a>
                </span>
            </div>
    
            <div class="mx-auto mt-4  px-8 text-center w-full">
                <div class="inline-flex flex-col flex-wrap items-center px-2 lg:px-5 py-2 lg:pt-2 lg:pb-4 mx-auto sm:flex-row ">
                    <p class="  mx-auto text-xs text-gray-300">© 2021 </p>
                </div>
            </div>
        </footer>
      </div>
    
      <script src="{{ asset('js/animation.js') }}"></script>
      <script src="{{ asset('js/home.js') }}"></script>
        <script>
          function toggleNavbar(collapseID) {
            document.getElementById(collapseID).classList.toggle("hidden");
            document.getElementById(collapseID).classList.toggle("block");
          }
        </script>
    </body>
    </html>