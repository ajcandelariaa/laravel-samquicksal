<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Samquicksal - Be our Partner</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    <style>
        html{
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="scrol">
    <header class="bg-adminLoginTextColor flex items-center flex-wrap font-Roboto">
        <img class="w-10 lg:w-20 ml-3 lg:ml-10 my-3" src="{{ asset('images/samquicksalLogo.png') }}" alt="samquicksalLogo">
        <button class="text-gray-200 inline-flex mr-1 p-3 rounded lg:hidden ml-auto transition duration-200 nav-toggler" data-target="#navigation">
            <svg class="h-8" xmlns="http://www.w3.org/2000/svg" className="" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
              </svg>
        </button>

        <div class="hidden top-navbar w-full lg:inline-flex lg:flex-grow lg:w-auto lg:text-2xl" id="navigation">
            <div class="lg:inline-flex lg:flex-row lg:ml-auto flex flex-col">
                <a href="#activationStep" class="lg:inline-flex lg:w-auto px-3 py-2 mx-5 rounded text-gray-200 hover:bg-headerActiveTextColor hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                    <span>Activation Steps</span>
                </a>
                <a href="#advantages" class="lg:inline-flex lg:w-auto px-3 py-2 rounded text-gray-200 hover:text-white mx-5 hover:bg-headerActiveTextColor hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                    <span>Advantages</span></a>
                <a href="#howItWorks" class="lg:inline-flex lg:w-auto px-3 py-2 rounded text-gray-200 hover:text-white mx-5 mr-8 hover:bg-headerActiveTextColor hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                    <span>How it works</span>
                </a>
            </div>
        </div>
    </header>

    <section class="relative">
        <div class="pt-32 lg:pt-96">
            <div class="relative">
                <h1 class="capitalize lg:ml-40 font-Montserrat font-bold lg:font-black text-blue-100 ml-20 lg:text-9xl absolute text-6xl -bottom-3 lg:-bottom-6 ">be our partner</h1>
            </div>
        </div>
    </section>

    <section class="bg-blue-100 relative">
        <div class="container flex flex-col items-center px-5 py-16 mx-auto lg:flex-row lg:px-20 z-10">
            <div class="flex flex-1 flex-col items-center lg:items-start">
                <p class="mb-8 text-md  lg:text-xl text-justify "> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Id diam vel quam elementum pulvinar etiam non quam. Libero enim sed faucibus turpis. Luctus venenatis lectus magna fringilla urna porttitor. Morbi tristique senectus et netus. Ut diam quam nulla porttitor massa id neque. Nam aliquam sem et tortor consequat id porta. Eget gravida cum sociis natoque. Suspendisse potenti nullam ac tortor. Volutpat est velit egestas dui id ornare arcu. Tellus in metus vulputate eu. Cras sed felis eget velit aliquet. </p>
                <p class="mb-16 text-md lg:text-xl text-justify  ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sed velit dignissim sodales ut eu sem integer vitae. Amet purus gravida quis blandit turpis cursus in. Ultricies mi quis hendrerit dolor magna eget est lorem. Diam phasellus vestibulum lorem sed risus ultricies tristique. A lacus vestibulum sed arcu non. Duis at consectetur lorem donec massa sapien faucibus et molestie. Dolor morbi non arcu risus quis varius quam. Faucibus turpis in eu mi bibendum neque egestas congue.</p>
            </div>
            <div class="flex justify-center flex-1 mb-10 md:mb-16 lg:mb-0 z-10 ">
                <img class=" rounded-2xl w-5/6 h-5/6 sm:w-3/4 sm:h-3/4 md:w-7/12 " src="{{ asset('images/homepage.png') }}" alt="homepage">
            </div>
        </div>
        <div class="hidden md:block overflow-hidden bg-loginLandingPage border-8 border-btn rounded-full absolute md:h-36 md:w-36 lg:h-80 lg:w-80 lg:-bottom-32 right-0"></div>
        <div class="hidden md:block overflow-hidden bg-headerActiveTextColor border-loginLandingPage border-8 rounded-full absolute md:h-16 md:w-16 lg:h-40 lg:w-40 lg:bottom-32 bottom-0  right-0"></div>
    </section>

    <section class="font-Montserrat relative" id="activationStep">
        <div class="mx-auto p-16 lg:my-36">
            <div class="flex justify-center">
                <h1 class="capitalize text-xl lg:text-5xl font-semibold text-center">activation steps</h1>
            </div>
            <div class="grid lg:grid-cols-3 p-3 lg:p-16">
                <div class="mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor lg:h-44 lg:w-52 h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <div class="flex flex-col text-center">
                        <span class="text-xs lg:text-lg">Step 1:</span>
                        <span class=" font-semibold text-xs lg:text-xl mb-4">Fill out the form</span>
                    </div>
                </div>

                <div class="mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor lg:h-44 lg:w-52 h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <div class="flex flex-col text-center">
                        <span class="text-xs lg:text-xl">Step 2:</span>
                        <span class="text-xs lg:text-xl font-semibold mb-4">Setup your Restaurant Info</span>
                    </div>
                </div>

                <div class="mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor lg:h-44 lg:w-52 h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <div class="flex flex-col text-center">
                        <span class="text-xs lg:text-xl">Step 3:</span>
                        <span class="font-semibold text-xs lg:text-xl ">Publish your Restaurant</span>    
                    </div>    
                </div>                   
            </div>
        </div>
    </section>

    <section class="bg-blue-100 relative" id="advantages">
        <div class="container flex flex-col items-start px-5 py-16 mx-auto lg:items-center md:flex-row lg:px-28">
            <div class="mx-auto mb-10 w-60 lg:w-5/6 lg:max-w-lg md:w-1/2">
                <img src="{{ asset('images/homepage.png') }}" alt="homepage">
            </div>
            <div class="flex flex-col items-start text-left lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16">
                <h1 class="lg:mb-16 mb-6 lg:text-5xl text-xl text-center font-Montserrat font-semibold tracking-tightertext-black "> Samquisksal Partner Advantages.</h1>
                
                <p class="flex mb-4 text-lg lg:text-3xl text-headerActiveTextColor font-semibold">
                    <span class="inline-flex items-center justify-center flex-shrink-0 w-5 lg:w-10 mr-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    Get Known to Potential Customers
                </p>

                <p class="flex text-xs lg:text-lg mb-3 lg:mb-10 ml-8 lg:ml-14 text-headerActiveTextColor font-Roboto ">Samquicksal can help your Restaurant get more potential customers. Engage customers with Loyalty Reward Card. </p>
                
                <p class="flex mb-4 text-lg lg:text-3xl text-headerActiveTextColor font-semibold">
                    <span class="inline-flex items-center justify-center flex-shrink-0 w-5 lg:w-10 mr-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <path d="M10 15.172l9.192-9.193 1.415 1.414L10 18l-6.364-6.364 1.414-1.414z"></path>
                    </span>
                    Easy Reservation and Ordering Process
                </p>
                
                <p class="flex text-xs lg:text-lg mb-3 lg:mb-10 ml-8 lg:ml-14 text-headerActiveTextColor font-Roboto">Longer waiting time no more. From Queuing and Reservation to Ordering, Samquicksal improves your dining experience </p>
                
                <p class="flex mb-4 text-lg lg:text-3xl text-headerActiveTextColor font-semibold">
                    <span class="inline-flex items-center justify-center flex-shrink-0 w-5 lg:w-10 mr-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-headerActiveTextColor" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Promo Announcement Made Easy
                </p>
                <p class="flex text-xs lg:text-lg mb-3 lg:mb-10 ml-8 lg:ml-14 text-headerActiveTextColor font-Roboto">Promos from your Restaurant are presented to customer within the application. </p>
            </div>
        </div>
    </section>

    <section class="relative" id="howItWorks">
        <div class="container flex flex-col items-center px-5 py-8 mx-auto">
            <div class="flex flex-col w-full mb-12 text-left">
                <div class="w-full mx-auto lg:w-1/2">
                    <h1 class="mx-auto lg:mb-8 mt-5 lg:mt-12 text-xl lg:text-5xl font-semibold leading-none tracking-tighter text-black flex justify-center items-center font-Montserrat"> How it works </h1>
                </div>
            </div>
            <div class="flex flex-col items-center pb-10 mx-auto mb-10 border-b sm:flex-row lg:w-1/2">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-16 h-16 text-black rounded-full sm:mr-10">
                    <img class=" h-12 lg:w-16 lg:h-16" src="{{ asset('images/iconQueue.png') }}" alt="iconQueue">
                </div>
            <div class="flex-grow mt-1 lg:mt-6 text-center sm:text-left sm:mt-0">
                <h2 class="mb-4 lg:mb-8 text-xl font-semibold leading-none tracking-tighter text-black lg:text-3xl title-font"> Step 1 </h2>
                <p class="font-medium leading-relaxed font-Roboto text-adminLoginTextColor text-xs lg:text-lg">Customer queues-in or reserves to your samgyeopsal restaurant via app or website 
                and can select nearby restaurant. Customer can view tasks to fulfill for rewards.</p>
            </div>
        </div>

        <div class="flex flex-col items-center pb-10 mx-auto mb-10 border-b sm:flex-row lg:w-1/2">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-16 h-16 text-black rounded-full sm:mr-10">
                <img class="h-12 lg:w-16 lg:h-16" src="{{ asset('images/iconNotify.png') }}" alt="iconNotify">
            </div>
            <div class="flex-grow mt-1 lg:mt-6 text-center sm:text-left sm:mt-0">
                <h2 class="mb-4 lg:mb-8 text-xl font-semibold leading-none tracking-tighter text-black lg:text-3xl title-font"> Step 2 </h2>
                <p class="font-medium leading-relaxed font-Roboto text-adminLoginTextColor text-xs lg:text-lg">Restaurant gets notified through the website when a customer queued or reserved.</p>
            </div>
        </div>

        <div class="flex flex-col items-center pb-10 mx-auto mb-10 border-b sm:flex-row lg:w-1/2">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-16 h-16 text-black rounded-full sm:mr-10">
                <img class="h-12 lg:h-16" src="{{ asset('images/iconSpoonAndKnives.png') }}" alt="iconSpoonAndKnives">
            </div>
            <div class="flex-grow mt-1 lg:mt-6 text-center sm:text-left sm:mt-0">
                <h2 class="mb-4 lg:mb-8 text-xl font-semibold leading-none tracking-tighter text-black lg:text-3xl title-font"> Step 3 </h2>
                <p class="font-medium leading-relaxed font-Roboto text-adminLoginTextColor text-xs lg:text-lg ">Once the customer get to dine-in, customer is presented with the menu within the app. 
                Restaurant gets notified with what the customer has added to be served.</p>
            </div>
        </div>
        <div class="flex flex-col items-center pb-10 mx-auto mb-10 border-b sm:flex-row lg:w-1/2">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-16 h-16 rounded-full sm:mr-10">
                <img class="h-12 lg:h-16 " src="{{ asset('images/iconPay.png') }}" alt="iconPay">
            </div>
            <div class="flex-grow mt-1 lg:mt-6 text-center sm:text-left sm:mt-0 z-10">
                <h2 class="mb-4 lg:mb-8 text-xl font-semibold leading-none tracking-tighter text-black lg:text-3xl title-font"> Step 4 </h2>
                <p class="font-medium leading-relaxed font-Roboto text-adminLoginTextColor text-xs lg:text-lg ">Customer can check out and pay using Gcash, save the receipt and upload it to the mobile app.</p>
            </div>
        </div>
        
        <div class="hidden md:block overflow-hidden bg-headerActiveTextColor border-8 border-header rounded-full absolute md:h-36 md:w-36 lg:h-80 lg:w-80 -top-32 left-0"></div>
        <div class="hidden md:block overflow-hidden bg-adminLoginTextColor border-8 border-btn rounded-full absolute md:h-16 md:w-16 lg:h-40 lg:w-40 lg:top-32 top-0 left-0"></div>
    </section>

    <section class="relative">
        <div class="flex flex-col items-center justify-center font-Montserrat font-semibold text-xs lg:text-lg z-10">
            <a class="flex-1  shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 text-center lg:h-3/4 lg:w-96 w-44 h-28 py-2 px-4 lg:py-4 lg:px-8 text-gray-50  bg-headerActiveTextColor mb-5 " href="/restaurant/register" target="_blank">Get Started</a>
            <a class="flex-1 shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 bg-gray-100 text-center lg:h-3/4 lg:w-96 w-44 h-28 py-2 px-4 lg:py-4 lg:px-8 text-headerActiveTextColor border-2 border-headerActiveTextColor mb-14" href="/restaurant/login" target="_blank">I’m already registered</a>
        </div>
    </section>

    <section class=" items-center">
        <footer class=" transition duration-500 ease-in-out transform bg-white border ">
          <div class="flex flex-col flex-wrap justify-center p-5 md:flex-row">
            <nav class="flex flex-wrap items-center justify-center w-full mx-auto mb-6 text-base nprd">
              <a href="#" class="px-4 py-1 mr-1 text-base text-blueGray-500 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-black ">About</a>
              <a href="#" class="px-4 py-1 mr-1 text-base text-blueGray-500 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-black ">Contact</a>
              <a href="#" class="px-4 py-1 mr-1 text-base text-blueGray-500 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-black ">Member</a>
              <a  class="mr-5" href="https://icons8.com/icon/84170/alarms"> (Icons by Icons8)</a>
            </nav>
            <span class="inline-flex justify-center w-full mx-auto mt-2 mr-2 sm:ml-auto sm:mt-0">
              <a class="text-blue-500 hover:text-btn">
                <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                  <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                </svg>
              </a>
              <a class="ml-3 text-blue-500 hover:text-btn">
                <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                  <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z">
                  </path>
                </svg>
              </a>
              <a class="ml-3 text-blue-500 hover:text-btn">
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                  <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                  <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                </svg>
              </a>
            </span>
          </div>
        </footer>
    </section>
            
    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>