<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css2') }}">
    <link rel="stylesheet" href="{{ asset('css/main.scss') }}">
    <title>Be our Partner | Samquicksal</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    <style>
        html{
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <header class=" header_1 top-0 z-50 w-full flex flex-wrap items-center justify-between px-2">
        <div class="container px-4 mx-auto flex flex-wrap items-center justify-between">
          <div class="w-full relative flex justify-between lg:w-auto lg:static lg:block lg:justify-start">
            <a class="text-sm font-bold leading-relaxed inline-block mr-4 py-2 whitespace-nowrap uppercase text-white" href="/"><img class="w-36 xl:w-36" src="{{ asset('images/samquicksalWithWords.png') }}" alt="samquicksalWithWords"></a>
      
            <button class="cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none" type="button" onclick="toggleNavbar('example-collapse-navbar')">
              <i class="text-submitButton fas fa-bars "></i>
            </button>
          </div>
          <nav class="lg:flex flex-grow items-center bg-adminLoginTextColor lg:bg-transparent lg:shadow-none hidden" id="example-collapse-navbar">
            <ul class="flex flex-col lg:flex-row list-none lg:ml-auto font-Montserrat">
              <li class="flex items-center">
                <a class="nav__link cursor-pointer text-submitButton px-7 py-4 lg:py-2 flex items-center text-xs uppercase font-bold" href="#activationStep">
                <p class="md:hidden sm:hidden hidden xl:contents">Activation Steps</p>
                    <span class="xl:hidden inline-block ml-2 ">Activation Steps</span><a>
              </li>
              <li class="flex items-center">
                <a class=" nav__link cursor-pointer px-7 py-4 lg:py-2 flex items-center text-xs uppercase font-bold " href="#advantages">
                  <p class="md:hidden sm:hidden hidden xl:contents">Advantages</p>
                <span class="xl:hidden inline-block ml-2 ">Advantages</span></a>
              </li>
              <li class="flex items-center">
                <a class="nav__link cursor-pointer text-submitButton px-7 py-4 lg:py-2 flex items-center text-xs uppercase font-bold "href="#howItWorks">
                  <p class="md:hidden sm:hidden hidden xl:contents">How It Works</p>
                  <span class="xl:hidden inline-block ml-2 ">How It Works</span></a>
              </li>
            </ul>
          </nav>
        </div>
      </header>

    {{-- <main>
      <section class="home-intro">
        <h1>Intersection Observer is pretty useful</h1>
      </section>

      <div class="home-about">
        <h2>About us</h2>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
        <div class="columns">
          <div class="col fade-in">
            <h3>Lorem, ipsum.</h3>
            <p>
              Lorem ipsum dolor sit, amet consectetur adipisicing elit. Vitae
              maiores fuga eos provident voluptas perferendis.
            </p>
          </div>
          <div class="col fade-in">
            <h3>A, illo!</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit.
              Voluptatibus minima quo beatae eius blanditiis officiis.
            </p>
          </div>
          <div class="col fade-in">
            <h3>Repudiandae, error?</h3>
            <p>
              Lorem ipsum dolor, sit amet consectetur adipisicing elit. Laborum
              quasi quis doloribus quia illum laudantium.
            </p>
          </div>
        </div>
      </div>
      <div class="home-more-stuff">
        <div class="more-stuff-grid">
          <img
            src="https://unsplash.it/400"
            alt=""
            class="slide-in from-left"
          />
          <p class="slide-in from-right">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis fugit,
            quae beatae vero sit magni quaerat id ratione. Dolor optio unde amet
            omnis sapiente neque cumque consequuntur reiciendis deserunt.
            Dolorem vero exercitationem consequuntur, eligendi cupiditate
            debitis facilis quibusdam magni. Eveniet.
          </p>
        </div>
        <div class="more-stuff-grid">
          <p class="slide-in from-left">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis fugit,
            quae beatae vero sit magni quaerat id ratione. Dolor optio unde amet
            omnis sapiente neque cumque consequuntur reiciendis deserunt.
            Dolorem vero exercitationem consequuntur, eligendi cupiditate
            debitis facilis quibusdam magni. Eveniet.
          </p>
          <img
            src="https://unsplash.it/401"
            alt=""
            class="slide-in from-right"
          />
        </div>
        <div class="more-stuff-grid">
          <img src="//unsplash.it/400" alt="" class="slide-in from-left" />
          <p class="slide-in from-right">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis fugit,
            quae beatae vero sit magni quaerat id ratione. Dolor optio unde amet
            omnis sapiente neque cumque consequuntur reiciendis deserunt.
            Dolorem vero exercitationem consequuntur, eligendi cupiditate
            debitis facilis quibusdam magni. Eveniet.
          </p>
        </div>
        <div class="more-stuff-grid">
          <p class="slide-in from-left">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis fugit,
            quae beatae vero sit magni quaerat id ratione. Dolor optio unde amet
            omnis sapiente neque cumque consequuntur reiciendis deserunt.
            Dolorem vero exercitationem consequuntur, eligendi cupiditate
            debitis facilis quibusdam magni. Eveniet.
          </p>
          <img
            src="https://unsplash.it/401"
            alt=""
            class="slide-in from-right"
          />
        </div>
        <div class="more-stuff-grid">
          <img src="//unsplash.it/400" alt="" class="slide-in from-left" />
          <p class="slide-in from-right">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis fugit,
            quae beatae vero sit magni quaerat id ratione. Dolor optio unde amet
            omnis sapiente neque cumque consequuntur reiciendis deserunt.
            Dolorem vero exercitationem consequuntur, eligendi cupiditate
            debitis facilis quibusdam magni. Eveniet.
          </p>
        </div>
        <div class="more-stuff-grid">
          <p class="slide-in from-left">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis fugit,
            quae beatae vero sit magni quaerat id ratione. Dolor optio unde amet
            omnis sapiente neque cumque consequuntur reiciendis deserunt.
            Dolorem vero exercitationem consequuntur, eligendi cupiditate
            debitis facilis quibusdam magni. Eveniet.
          </p>
          <img
            src="https://unsplash.it/401"
            alt=""
            class="slide-in from-right"
          />
        </div>
      </div> --}}
{{-- sample --}}

    {{-- <nav class="top-0 absolute z-50 w-full flex flex-wrap items-center justify-between px-2 bg-adminLoginTextColor  ">
        <div class=" container px-4 mx-auto flex flex-wrap items-center justify-between ">
          <div class="w-full relative flex justify-between lg:w-auto lg:static lg:block lg:justify-start">
            <a class="text-sm font-bold leading-relaxed inline-block mr-4 pt-2 whitespace-nowrap uppercase text-white" href="/"><img class="w-36 xl:w-36" src="{{ asset('images/samquicksalWithWords.png') }}" alt="samquicksalWithWords"></a>
           
            <button class="cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none" type="button" onclick="toggleNavbar('example-collapse-navbar')">
              <i class="text-white fas fa-bars"></i>
            </button>
          </div>
          <div class="lg:flex flex-grow items-center lg:bg-transparent lg:shadow-none hidden" id="example-collapse-navbar">
            <ul class="flex flex-col lg:flex-row list-none lg:ml-auto font-Montserrat">
              <li class="flex items-center">
                <a class="cursor-pointer text-gray-100 hover:text-submitButton transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110  px-4 py-4 lg:py-2 flex items-center text-xs uppercase font-bold" href="#activationStep" >
                <p class="md:hidden sm:hidden xl:contents">Activation Steps</p>
                    <span class="xl:hidden inline-block ml-2">Activation Steps</span><a>
              </li>
              <li class="flex items-center">
                <a class="cursor-pointer text-gray-100 hover:text-submitButton transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 px-4 py-4 lg:py-2 flex items-center text-xs uppercase font-bold" href="#advantages">
                  <p class="md:hidden sm:hidden xl:contents">Advantages</p>
                <span class="xl:hidden inline-block ml-2">Advantages</span></a>
              </li>
              <li class="flex items-center">
                <a class="cursor-pointer text-gray-100 hover:text-submitButton transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110  px-4 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"  href="#howItWorks">
                  <p class="md:hidden sm:hidden xl:contents">How It Works</p>
                  <span class="xl:hidden inline-block ml-2">How It Works</span></a>
              </li>
            </ul>
          </div>
        </div>
      </nav> --}}
    {{-- sample --}}
    {{-- <header class="bg-adminLoginTextColor flex items-center flex-wrap">
        <div class="flex flex-row lg:inline-flex lg:w-auto">
            <img class="w-12 lg:w-16 ml-3 lg:ml-10 my-3" src="{{ asset('images/samquicksalLogo.png') }}" alt="samquicksalLogo">
            <h1 class="font-Molle text-darkerSubmitButton mt-7 text-2xl -m-3">amquicksal</h1>
        </div>
    <button class="text-gray-200 inline-flex mr-1 p-3 rounded lg:hidden ml-auto transition duration-200 nav-toggler  " data-target="#navigation">
        <svg class="h-8" xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
          </svg>
    </button>
    <div class="hidden top-navbar w-full lg:inline-flex lg:flex-grow lg:w-auto" id="navigation">
        <div class="lg:inline-flex lg:flex-row lg:ml-auto flex flex-col font-Roboto lg:text-lg">
            <a href="#activationStep" class="lg:inline-flex lg:w-auto px-3 py-0 mx-5 rounded text-gray-200 hover:text-submitButton  transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                <span>Activation Steps</span>
            </a>
            <a href="#advantages" class="lg:inline-flex lg:w-auto px-3 py-0 rounded text-gray-200 hover:text-submitButton mx-5  transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                <span>Advantages</span></a>
            <a href="#howItWorks" class="lg:inline-flex lg:w-auto px-3 py-0 rounded text-gray-200 hover:text-submitButton mx-5 mr-8  transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                <span>How it works</span>
            </a>
        </div>
    </div>
     </header> --}}
    {{-- sample --}}

    <section class="relative">
        <div class="pt-32 sm:pt-44 md:pt-44 lg:pt-64 xl:pt-64 home-intro">
            <div class="relative">
                <h1 class="absolute capitalize font-Montserrat font-bold text-adminViewAccountHeaderColor2 ml-10 sm:ml-20 md:ml-20 lg:ml-20 xl:ml-40 xl:text-8xl lg:text-7xl md:text-6xl sm:text-5xl text-4xl -bottom-2 sm:-bottom-2 md:-bottom-3 lg:-bottom-3 xl:-bottom-4 ">be our partner</h1>
            </div>
        </div>
    </section>

    <section class="bg-adminViewAccountHeaderColor2 relative">
        <div class="container flex flex-col sm:flex-row md:flex-row lg:flex-row xl:flex-row items-center md:py-2 lg:py-1 xl:py-1 mx-auto px-6 sm:px-8 md:px-12 lg:px-16 xl:px-20 ">
            <div class="flex flex-1 flex-col items-center xl:items-start leading-tight tracking-tighter">
                <p class="mb-2 sm:mb-2 md:mb-3 lg:mb-3 xl:mb-8 mt-3 sm:mt-4 md:mt-7 lg:mt-8 xl:mt-10 xl:text-base lg:text-base md:text-xs sm:text-xs text-sm text-justify"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Id diam vel quam elementum pulvinar etiam non quam. Libero enim sed faucibus turpis. Luctus venenatis lectus magna fringilla urna porttitor. Morbi tristique senectus et netus. Ut diam quam nulla porttitor massa id neque. Nam aliquam sem et tortor consequat id porta.  </p>
                <p class="xl:mb-10 lg:mb-5 md:mb-4 sm:mb-2 mb-2 xl:text-base lg:text-base md:text-xs sm:text-xs text-sm text-justify  ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sed velit dignissim sodales ut eu sem integer vitae. Amet purus gravida quis blandit turpis cursus in. Ultricies mi quis hendrerit dolor magna eget est lorem. Diam phasellus vestibulum lorem sed risus ultricies tristique. A lacus vestibulum sed arcu non. Duis at consectetur lorem donec massa sapien faucibus et molestie. Dolor morbi non arcu risus quis varius quam. Faucibus turpis in eu mi bibendum neque egestas congue.</p>
            </div>
            <div class="flex justify-center flex-1 mt-5 mb-10 md:mb-10 lg:mb-16 xl:my-10 ">
                <img class="w-5/12 sm:w-6/12 md:w-6/12 lg:w-7/12 xl:w-5/12" src="{{ asset('images/girlBoyBlack.png') }}" alt="girlBoyBlack">
            </div>
        </div>
        <div class=" hidden sm:block overflow-hidden bg-loginLandingPage border-btn rounded-full absolute sm:h-24 sm:w-24 md:h-24 md:w-24 lg:h-44 lg:w-44 xl:h-52 xl:w-52  xl:inset-y-72 lg:inset-y-96 md:inset-y-56 sm:inset-y-56 right-0 filter blur-xl"></div>
        <div class=" hidden sm:block overflow-hidden flex-1 bg-headerActiveTextColor rounded-full absolute sm:h-14 sm:w-14 md:h-14 md:w-14 lg:h-24 lg:w-24 xl:h-28 xl:w-28 sm:inset-y-48 md:inset-y-48 lg:inset-y-3/4 xl:inset-y-60 right-0 filter blur-xl"></div>
        
    </section>
    

    <section class="font-Montserrat relative" id="activationStep">
        <div class="mx-auto p-8 sm:p-10 md:p-10 lg:p-16 xl:p-16 xl:my-36 lg:mt-16 md:mt-16 sm:mt-10 mt-8 ">
            <div class="flex justify-center">
                <h1 class="capitalize text-lg sm:text-lg md:text-xl lg:text-2xl xl:text-4xl font-semibold text-center col fade-in">activation steps</h1>
            </div>
            <div class="grid sm:grid-cols-3 sm:p-4 md:p-10 lg:p-14 xl:p-16">
                <div class="mx-auto">
                    {{-- <img class=" text-headerActiveTextColor w-4/12 sm:w-6/12 md:w-7/12 lg:w-8/12 xl:w-full lg:mt-4 md:mt-2 sm:mt-3 mt-4 xl:ml-4 lg:ml-9 md:ml-10 sm:ml-11 ml-16 animate-wiggle" src="{{ asset('images/clarity_form-line.png') }}" alt="filloutIcon"> --}}
                   <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor w-3/12 sm:w-6/12 md:w-6/12 lg:w-6/12 xl:w-7/12 mx-auto animate-wiggle col fade-in " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    <div class="flex flex-col text-center">
                        <span class="text-base sm:text-xs md:text-sm lg:text-lg xl:text-xl md:mt-1 col fade-in">Step 1:</span>
                        <span class=" font-semibold text-sm sm:text-xxs md:text-xs lg:text-base xl:text-xl mb-4 col fade-in">Fill out the form</span>
                    </div>
                </div>

                <div class="mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor  xl:w-7/12 lg:w-6/12 md:w-6/12 sm:w-6/12 w-3/12 mx-auto animate-spin col fade-in" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <div class="flex flex-col text-center">
                        <span class="text-base sm:text-xs md:text-sm lg:text-lg xl:text-xl md:mt-1 col fade-in ">Step 2:</span>
                        <span class="text-sm sm:text-xxs md:text-xs lg:text-base xl:text-xl font-semibold mb-4 col fade-in">Setup your Restaurant Info</span>
                    </div>
                </div>

                <div class="mx-auto">
                      <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor w-3/12 sm:w-6/12 md:w-6/12  lg:w-6/12 xl:w-7/12 mx-auto animate-wiggle col fade-in" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                      </svg>
                    <div class="flex flex-col text-center">
                        <span class="text-base sm:text-xs md:text-sm lg:text-lg xl:text-xl md:mt-1 col fade-in">Step 3:</span>
                        <span class="text-sm sm:text-xxs font-semibold md:text-xs lg:text-base xl:text-xl mb-10 col fade-in">Publish your Restaurant</span>    
                    </div>    
                </div>                   
            </div>
        </div>
    </section>

    <section class="bg-adminViewAccountHeaderColor2 relative" id="advantages">
        {{-- sample --}}
        <div class="container flex flex-col-reverse xl:flex-row lg:flex-row md:flex-row sm:flex-row items-start xl:py-16 lg:py-14 md:py-5 mx-auto xl:items-center px-3 sm:px-0 md:px-0 lg:px-28 xl:px-28">
            <div class=" flex justify-center md:justify-start lg:justify-start xl:justify-start xl:mb-10 mb-6 md:ml-24   md:mt-10 sm:mt-10 lg:-ml-10 ">
                <img class="w-4/12 sm:w-7/12 md:w-5/12 lg:w-9/12 xl:w-9/12 slide-in from-left" src="{{ asset('images/partners.png') }}" alt="partners">
            </div>
            <div class="flex flex-col sm:items-start md:items-start lg:items-start xl:items-start items-center lg:flex-grow xl:pl-0 md:pl-16 xl:-ml-0 lg:-ml-32 md:-ml-72 sm:-ml-5 ">
                <h1 class="xl:mb-16 lg:mb-5 mb-3 text-base xl:text-4xl lg:text-2xl md:text-xl sm:text-lg lg:text-left lg:-ml-10 xl:text-left sm:mt-5 mt-7 font-Montserrat font-semibold tracking-tighter text-black col fade-in">Samquisksal Partner Advantages.</h1>
                <div class="flex justify-center">
                    <span class="inline-flex items-center justify-center flex-shrink-0 w-6 sm:w-8 md:w-8 lg:w-8 xl:w-14 mr-3 sm:mr-3 md:mr-3 xl:mr-7 lg:mr-7 xl:mb-6 rounded-full col fade-in">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <path d="M10 15.172l9.192-9.193 1.415 1.414L10 18l-6.364-6.364 1.414-1.414z"></path>
                    </span>
                <div class="flex flex-col mt-3 xl:mt-4">
                    <p class="text-sm sm:text-base md:text-sm lg:text-lg xl:text-2xl text-headerActiveTextColor font-semibold col fade-in">Get Known to Potential Customers</p>
                    <p class="text-xxs sm:text-xs xl:text-base mb-3 xl:mb-10 text-headerActiveTextColor font-Roboto col fade-in">Samquicksal can help your Restaurant get more potential customers with Loyalty Reward Card. </p>
                 </div>
                </div>
                <div class="flex justify-center">
                    <span class="inline-flex items-center justify-center flex-shrink-0 w-6 sm:w-8 md:w-8 lg:w-8 xl:w-14 mr-3 sm:mr-3 md:mr-3 xl:mr-7 lg:mr-7 xl:mb-6 rounded-ful col fade-in">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <path d="M10 15.172l9.192-9.193 1.415 1.414L10 18l-6.364-6.364 1.414-1.414z"></path>
                    </span>
                <div class="flex flex-col mt-4">
                    <p class="text-sm sm:text-base md:text-sm lg:text-lg xl:text-2xl text-headerActiveTextColor font-semibold col fade-in">Easy Reservation and Ordering Process</p>
                    <p class="text-xxs sm:text-xs md:text-xs lg:text-base xl:text-base mb-3 xl:mb-10 text-headerActiveTextColor font-Roboto col fade-in "> From Queuing and Reservation to Ordering, Samquicksal improves your dining experiencece.  </p>
                 </div>
                </div>

                <div class="flex justify-center">
                    <span class="inline-flex items-center justify-center flex-shrink-0 w-6 sm:w-8 md:w-8 lg:w-8 xl:w-14 mr-3 sm:mr-3 md:mr-3 xl:mr-7 lg:mr-7 xl:mb-6 rounded-full col fade-in">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <path d="M10 15.172l9.192-9.193 1.415 1.414L10 18l-6.364-6.364 1.414-1.414z"></path>
                    </span>
                <div class="flex flex-col mt-4">
                    <p class="text-sm sm:text-base md:text-sm lg:text-lg xl:text-2xl text-headerActiveTextColor font-semibold col fade-in">Promo Announcement Made Easy</p>
                    <p class="text-xxs sm:text-xs md:text-xs lg:text-base mb-3 xl:mb-10 text-headerActiveTextColor font-Roboto col fade-in ">Promos from your Restaurant are presented to customer within the application. </p>
                 </div>
                </div>
            </div>
          </div>
    </section>
    
    <section class="relative " id="howItWorks">
        <div class="container flex flex-col items-center px-14 sm:px-20 md:px-20 lg:px-40 py-10 sm:py-10 md:py-10 lg:py-16 xl:px-10 xl:py-8 mx-auto">
            <div class="flex flex-col w-full mb-12 text-left">
                <div class="w-full mx-auto xl:w-1/2">
                    <h1 class="mx-auto xl:mb-8 lg:mb-8 md:mb-3 sm:mt-10 md:mt-20 lg:mt-14 xl:mt-12 text-lg sm:text-lg md:text-xl lg:text-2xl xl:text-4xl font-semibold leading-none text-black flex justify-center items-center font-Montserrat col fade-in"> How it works </h1>
                </div>
            </div>
            <div class="flex flex-col items-center pb-10 mx-auto mb-10 border-b sm:flex-row xl:w-1/2">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-16 h-16 sm:w-16 sm:h-16 md:w-16 md:h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 bg-adminLoginTextColor border-submitButton border-2 shadow-2xl rounded-full sm:mr-10 slide-in from-left ">
                    <img class="h-10 w-10 sm:h-10 sm:w-10 md:h-11 md:w-11 lg:h-10 lg:w-10 xl:w-14 xl:h-14 col fade-in " src="{{ asset('images/iconQueue.png') }}" alt="iconQueue">
                </div>
            <div class="flex-grow lg:mt-1 xl:mt-6 sm:text-left md:text-left lg:text-left xl:text-left">
                <h2 class="text-center sm:text-left md:text-left lg:text-left xl:text-left  mt-2 sm:mt-0 md:mt-0 lg:mt-0 xl:mt-0 mb-2 sm:mb-2 md:mb-2 lg:mb-4 xl:mb-8 text-base sm:text-base md:text-base lg:text-xl xl:text-3xl font-semibold text-adminLoginTextColor title-font col fade-in">Step 1 </h2>
                <p class="text-justify sm:text-left md:text-left lg:text-left xl:text-left  font-medium leading-none font-Roboto text-adminLoginTextColor text-sm sm:text-sm md:text-sm lg:text-base xl:text-lg ">Customer queues-in or reserves to your samgyeopsal restaurant via app or website 
                and can select nearby restaurant. Customer can view tasks to fulfill for rewards.</p>
            </div>
        </div>

        <div class="flex flex-col items-center pb-10 mx-auto mb-10 border-b sm:flex-row xl:w-1/2">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-16 h-16 sm:w-16 sm:h-16 md:w-16 md:h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 bg-submitButton border-adminLoginTextColor border-2 shadow-2xl rounded-full sm:mr-10 slide-in from-left">
                <img class="h-10 w-10 sm:h-10 sm:w-10 md:h-11 md:w-11 lg:h-10 lg:w-10 xl:w-14 xl:h-14 col fade-in" src="{{ asset('images/iconNotify.png') }}" alt="iconNotify">
            </div>
            <div class="flex-grow lg:mt-1 xl:mt-6 text-left">
                <h2 class="text-center sm:text-left md:text-left lg:text-left xl:text-left  mt-2 sm:mt-0 md:mt-0 lg:mt-0 xl:mt-0 mb-2 sm:mb-2 md:mb-2 lg:mb-4 xl:mb-8 text-base sm:text-base md:text-base lg:text-xl xl:text-3xl font-semibold text-adminLoginTextColor title-font col fade-in"> Step 2 </h2>
                <p class="text-justify sm:text-left md:text-left lg:text-left xl:text-left  font-medium leading-none font-Roboto text-adminLoginTextColor text-sm sm:text-sm md:text-sm lg:text-base xl:text-lg col fade-in">Restaurant gets notified through the website when a customer queued or reserved.</p>
            </div>
        </div>

        <div class="flex flex-col items-center pb-10 mx-auto mb-10 border-b sm:flex-row xl:w-1/2">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-16 h-16 sm:w-16 sm:h-16 md:w-16 md:h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 bg-adminLoginTextColor border-submitButton border-2 shadow-2xl rounded-full sm:mr-10 slide-in from-left">
                <img class="h-10 w-10 sm:h-10 sm:w-10 md:h-11 md:w-11 lg:h-10 lg:w-10 xl:w-14 xl:h-14 col fade-in" src="{{ asset('images/iconSpoonAndKnives.png') }}" alt="iconSpoonAndKnives">
            </div>
            <div class="flex-grow lg:mt-1 xl:mt-6 text-left">
                <h2 class="text-center sm:text-left md:text-left lg:text-left xl:text-left  mt-2 sm:mt-0 md:mt-0 lg:mt-0 xl:mt-0 mb-2 sm:mb-2 md:mb-2 lg:mb-4 xl:mb-8 text-base sm:text-base md:text-base lg:text-xl xl:text-3xl font-semibold text-adminLoginTextColor title-font col fade-in"> Step 3 </h2>
                <p class="text-justify sm:text-left md:text-left lg:text-left xl:text-left  font-medium leading-none font-Roboto text-adminLoginTextColor text-sm sm:text-sm md:text-sm lg:text-base xl:text-lg col fade-in">Once the customer get to dine-in, customer is presented with the menu within the app. 
                Restaurant gets notified with what the customer has added to be served.</p>
            </div>
        </div>
        <div class="flex flex-col items-center pb-10 mx-auto mb-10 border-b sm:flex-row xl:w-1/2">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-16 h-16 sm:w-16 sm:h-16 md:w-16 md:h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 bg-submitButton border-adminLoginTextColor border-2 shadow-2xl rounded-full sm:mr-10 slide-in from-left">
            <img class="h-10 w-10 sm:h-10 sm:w-10 md:h-11 md:w-11 lg:h-10 lg:w-10 xl:w-14 xl:h-14" src="{{ asset('images/iconPay.png') }}" alt="iconPay">
            </div>
            <div class="flex-grow lg:mt-1 xl:mt-6 text-left">
                <h2 class="text-center sm:text-left md:text-left lg:text-left xl:text-left  mt-2 sm:mt-0 md:mt-0 lg:mt-0 xl:mt-0 mb-2 sm:mb-2 md:mb-2 lg:mb-4 xl:mb-8 text-base sm:text-base md:text-base lg:text-xl xl:text-3xl font-semibold text-adminLoginTextColor title-font col fade-in"> Step 4 </h2>
                <p class="text-justify sm:text-left md:text-left lg:text-left xl:text-left  font-medium leading-none font-Roboto text-adminLoginTextColor text-sm sm:text-sm md:text-sm lg:text-base xl:text-lg col fade-in">Customer can check out and pay using Gcash, save the receipt and upload it to the mobile app.</p>
            </div>
        </div>

        <div class="shadow-2xl hidden sm:block overflow-hidden bg-headerActiveTextColor border-header rounded-full absolute sm:h-24 sm:w-24 md:h-24 md:w-24 lg:h-44 lg:w-44 xl:h-52 xl:w-52 sm:-top-16 md:-top-16 lg:-top-32 xl:-top-32 left-0 filter blur-xl animate-blob animation-delay-4000 col fade-in"></div>

        <div class="hidden sm:block overflow-hidden bg-adminLoginTextColor border-btn rounded-full absolute sm:h-14 sm:w-14 md:h-14 md:w-14 lg:h-24 lg:w-24 xl:h-28 xl:w-28 sm:inset-y-0 md:inset-y-0 lg:inset-y-0 xl:inset-x-0 left-0 filter blur-xl animate-blob animation-delay-2000 col fade-in">
        </div>
        
    </section>

    <section class="relative">
        <div class="flex flex-col items-center justify-center font-Montserrat font-semibold text-xs sm:text-sm md:text-sm lg:text-base xl:text-lg">
            <a class="flex-1 shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110  text-center hover:bg-adminLoginTextColor hover:text-submitButton hover:border-submitButton font-bold border-2 border-gray-100  xl:h-3/4 xl:w-96 lg:w-64 lg:h-32 md:w-60 md:h-32 sm:h-32 sm:w-60  h-32 w-48 py-2 px-3 xl:py-4 xl:px-8 text-gray-50  bg-headerActiveTextColor mb-5 xl:mt-1" href="/restaurant/register" target="_blank">Get Started</a>
            <a class="flex-1 shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 hover:text-gray-100 hover:bg-adminLoginTextColor bg-white text-center xl:h-3/4 xl:w-96 lg:w-64 lg:h-32 md:w-60 md:h-32 sm:h-32 sm:w-60 h-32 w-48  py-2 px-3 xl:py-4 xl:px-8 mb-16 xl:mb-36 text-headerActiveTextColor border-2 border-headerActiveTextColor" href="/restaurant/login" target="_blank">I’m already registered</a>
        </div>
    </section>

    <footer class=" transition duration-500 ease-in-out transform bg-adminLoginTextColor ">
        <div class="flex flex-col flex-wrap justify-center p-2 lg:p-5 md:flex-row">
            <nav class="flex flex-wrap items-center justify-center w-full mx-auto mb-1 lg:mb-6 text-base">
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

            
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/animation.js') }}"></script>
    <script>
        function toggleNavbar(collapseID) {
          document.getElementById(collapseID).classList.toggle("hidden");
          document.getElementById(collapseID).classList.toggle("block");
        }
      </script>
</body>
</html>