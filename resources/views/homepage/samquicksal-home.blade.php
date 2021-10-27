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
       {{-- sample 2 --}}
       {{-- sample 2 --}}
    <title>Samquicksal</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    
    <script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    @if (session()->has('passwordUpdated'))
        <script>
            Swal.fire(
                'Your password has been updated',
                'You can now login again',
                'success'
            );
        </script>
    @endif
   <section style="background-image: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0)), url({{asset('images/restaurant.png')}}); background-repeat: no-repeat; background-size: cover;" class="w-full h-full">
    <header class="flex items-center flex-wrap">
            <div class="flex flex-row lg:inline-flex lg:w-auto mt-2">
                <img class="w-40 lg:w-40 ml-3 lg:ml-10 my-3" src="{{ asset('images/samquicksalLogoText.png') }}" alt="samquicksalLogo">
            </div>
        <button class="text-gray-200 inline-flex mr-1 p-3 rounded lg:hidden ml-auto transition duration-200 nav-toggler  " data-target="#navigation">
            <svg class="h-8" xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
              </svg>
        </button>
        <div class="hidden top-navbar w-full lg:inline-flex lg:flex-grow lg:w-auto" id="navigation">
            <div class="lg:inline-flex lg:flex-row lg:ml-auto flex flex-col font-Roboto lg:text-lg">
                <a href="/restaurant" target="_blank" class="lg:inline-flex lg:w-auto px-3 py-0 mx-5 rounded text-submitButton hover:text-gray-50 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110">Be Our Partner</a>
                <a href="#" class="lg:inline-flex lg:w-auto px-3 py-0 mx-5  rounded text-submitButton hover:text-gray-50 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ">Login</a>
                <a href="#" class="lg:inline-flex lg:w-auto px-3 py-0 rounded mx-5 mr-8 text-submitButton hover:text-gray-50 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110">Signup</a>
            </div>
        </div>
    </header>
    {{-- Sample --}}
            <div class=" grid lg:grid-cols-3 py-16 lg:py-32 lg:mt-20 lg:pl-20 w-9/12 mx-auto text-gray-200">
                <div class="lg:ml-5 mb-5 text-center lg:text-left w-10/12 lg:col-span-2 flex items-center justify-center flex-col mx-auto">
                    <h1 class="lg:text-5xl md:text-2xl text-xl font-Montserrat font-medium">“The Quick way to Samgyup”</h1>
                    <p class="font-Roboto mt-1 lg:mt-5 lg:ml-1 text-xs lg:text-2xl font-medium">Treat Yourself using Samquicksal! </p>
                    <div class="font-Roboto border-submitButton text-submitButton border-2 rounded-full uppercase mt-5 lg:mt-24 lg:py-3 lg:px-16 px-3 py-1 lg:text-xl text-xs cursor-pointer tracking-wider hover:bg-submitButton hover:text-gray-50 shadow-inner hover:shadow-xl transition duration-200 ">
                        <a href="#" class="font-bold">Dine in</a>
                    </div>
                </div>
                <div class="w-40 md:w-42 lg:w-96 mx-auto">
                    <img src="{{ asset('images/girlBoyPink.png') }}" alt="samquicksalLogo">
                </div>
            </div>
    </section>

    <section  class="grid lg:grid-cols-2 lg:py-36 relative ">
            <div class="mx-auto w-32 lg:w-4/6">
                <img class="lg:my-20 mt-8" src="{{ asset('images/samquicksalLogo.png') }}" alt="samquicksalLogo">
            </div>
            <div class="z-10 text-submitButton font-Montserrat font-bold text-xs lg:text-xl px-10 mt-3 lg:p-16">
                <p>Be part of an exclusive club, If you enjoy eating samgyup you'll love this. The loyalty rewards card gives its members discounts upon completion of the 10 stamps on the card. Stamps will be gained through the tasks given depending on the restaurant. Download the app and get your own card now! In the mood <br><br> for samgyupsal today? Use our queueing feature to queue yourself,friends and family in! It also lets you know how many customers are in the restaurant so you can go ahead and select another one if you're in a rush! See its Samquick! Looking to get that samgyup fix in advance? Use the booking feature to reserve your slot ahead of the others to ensure you get your table and know when to get there!</p>
                <p class="mt-2 lg:mt-4">Be the first to hear about the exclusive promotions that our registered restaurants have to offer such as special lunch rates, holiday discounts, or exclusive promos for their anniversaries. Stamp card for users that can be used for exclusive discounts and many more No more guessing, you’ll know when your table is ready when you get a notification from our app! Reserve or queue in seconds, Choose the restaurant fill in the form and you're ready to go!</p>
            </div>
            <div class="hidden overflow-hidden absolute lg:block rounded-lg bg-indigo-200 top-80 h-80 w-6/12 right-0 z-0"></div>
    </section>
    
    <section style="background-image: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0)), url({{asset('images/phones.jpg')}}); background-repeat: no-repeat; background-size: cover;" class="w-full h-96">
    </section>

    
    <section class="font-Montserrat mt-20">
        <div class="container flex flex-col items-start px-5 py-16 mx-auto lg:items-center md:flex-row lg:px-28">
          <div class="mx-auto mb-10 lg:w-5/6 lg:max-w-lg md:w-1/2">
            <div class="bg-submitButton text-white rounded-xl ">
                <div class="flex justify-center">
                    <h1 class="uppercase font-black text-2xl lg:text-5xl ml-3 mr-2 my-4">no</h1>
                <div class="flex flex-col mr-2 mt-4 text-xs lg:text-lg">
                    <p>Long Wait Hours!</p>
                    <p>More Falling in Line!</p>
                </div>
            </div>
            <div class="flex justify-center items-center">
                <img class="w-28 md:w-56 lg:w-2/3 rounded-t-xl shadow-3xl" src="{{ asset('images/muhanUnilimitedSamgyupsal.png') }}" alt="muhanUnilimitedSamgyupsal">
            </div>
          </div>
          </div>
          <div class="flex flex-col text-center lg:text-left lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16 relative">
           <h2 class="mb-8 font-bold text-black text-xl lg:text-5xl my-4">Samquicksal</h2>
            <h1 class="mb-8 text-sm lg:text-2xl font-bold tracking-tighter text-left text-black ">Samquicksal has Booking and Queueing so you don't have to wait in line anymore.</h1>
            <p class="mb-8 text-sm lg:text-lg leading-relaxed text-left ">In the mood for samgyupsal today? Use our queueing feature to queue yourself,friends and family in! It also lets you know how many customers are in the restaurant so you can go ahead and select another one if you're in a rush! See its Samquick!</p>
            <p class="mb-8 text-sm lg:text-lg leading-relaxed text-left ">Looking to get that samgyup fix in advance? Use the booking feature to reserve your slot ahead of the others to ensure you get your table and know when to get there!</p>
            <p class="flex mb-2 text-xs lg:text-lg"><span class="inline-flex items-center justify-center flex-shrink-0 w-4 h-4 lg:w-6 lg:h-6 mr-2 rounded-full">
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                  <path fill="none" d="M0 0h24v24H0z"></path>
                  <path d="M10 15.172l9.192-9.193 1.415 1.414L10 18l-6.364-6.364 1.414-1.414z"></path>
                </svg></span>No more long wait hours</p>
            <p class="flex mb-6 text-xs lg:text-lg"><span class="inline-flex items-center justify-center flex-shrink-0 w-4 h-4 lg:w-6 lg:h-6 mr-2 rounded-full">
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                  <path fill="none" d="M0 0h24v24H0z"></path>
                  <path d="M10 15.172l9.192-9.193 1.415 1.414L10 18l-6.364-6.364 1.414-1.414z"></path>
                </svg></span>No more falling in line</p>
          </div>
        </div>
      </section>

      <section class="font-Montserrat ">
        <div class="container flex flex-col items-center px-5 py-16 mx-auto md:flex-row lg:px-28">
          <div class="flex flex-col items-start w-full pt-0 mb-16 text-left lg:flex-grow md:w-1/2 xl:mr-20 md:pr-24 md:mb-0 ">
            <h1 class="mb-8 text-2xl font-bold tracking-tighter text-left text-black lg:text-2xl title-font"> Loyalty has never been so filling </h1>
            <p class="mb-8 text-xs lg:text-lg leading-relaxed text-left ">Be part of an exclusive club, If you enjoy eating samgyup you'll love this. The loyalty rewards card gives its members discounts upon completion of the 10 stamps on the card. </p>
            <p class="mb-8 text-xs lg:text-lg leading-relaxed text-left "> Stamps will be gained through the tasks given depending on the restaurant. Download the app and get your own card now! </p>
          </div>
          <div class="mx-auto mb-10 -mt-16 lg:mt-0 lg:w-5/6 lg:max-w-lg md:w-1/2">
            <div class="bg-boxPeach text-submitButton rounded-xl relative pb-28 md:pb-72 lg:pb-96 shadow-xl">
                <div class="flex flex-col py-5 px-5 lg:py-5 lg:px-7 text-lg lg:text-3xl font-bold">
                    <p>Earn Rewards for</p>
                    <p>Completing</p>
                    <p>Our Loyalty Card</p>
                </div>
   
            <div class="flex justify-center items-center absolute ">
                <img class="md:w-46 lg:w-full rounded-t-xl" src="{{ asset('images/rewards.png') }}" alt="rewards">
            </div>
        </div>
      </section>

  <div class="bg-gradient-to-b from-white to-headerBgColor">

      <section class="text-submitButton font-Montserrat mt-40 lg:mt-72">
          <div class="container flex flex-col items-center lg:px-5 lg:py-8 mx-auto">
            <div class="flex flex-col w-full text-left ">
              <div class="w-full mx-auto lg:w-1/2">
                <h1 class="mx-auto mb-4 lg:mb-24 text-xl leading-none tracking-tighter lg:text-5xl flex justify-center items-center font-bold ">More Benefits to Enjoy </h1>
              </div>
            </div>
            <div class="flex flex-col items-center pb-10 mx-auto mb-10 sm:flex-row lg:w-1/2">
              <div class="inline-flex items-center justify-center flex-shrink-0 w-20 h-20 rounded-ful sm:mr-10">
                  <div class="inline-flex items-center justify-center flex-shrink-0  rounded-full sm:mr-10">
                      <img class=" h-12 lg:h-32  " src="{{ asset('images/getNotifiedIcon.png') }}" alt="waitingLogo">
                  </div>
              </div>
              <div class="flex-grow mt-1 lg:mt-6 text-center sm:text-left sm:mt-0">
                <h2 class="mb-2 lg:mb-1 text-lg font-semibold leading-none tracking-tighter lg:text-3xl"> Get Notified</h2>
                <p class="text-xs lg:text-lg leading-relaxed px-9 lg:px-0 lg:mb-0 -mb-14">Reserve or queue in seconds, Choose the restaurant fill in the form and you're ready to go!</p>
              </div>
            </div>
            <div class="flex flex-col items-center pb-10 mx-auto mb-10 sm:flex-row lg:w-1/2">
              <div class="inline-flex items-center justify-center flex-shrink-0 w-20 h-20 sm:mr-10">
                  <div class="inline-flex items-center justify-center flex-shrink-0  sm:mr-10">
                      <img class=" h-12 lg:h-32 " src="{{ asset('images/noLongWaitHoursIcon.png') }}" alt="waitingLogo">
                  </div>
              </div>
              <div class="flex-grow mt-1 lg:mt-6 text-center sm:text-left sm:mt-0">
                  <h2 class="mb-2 lg:mb-1 text-lg font-semibold leading-none tracking-tighter lg:text-3xl"> No Long Wait Hours!</h2>
                  <p class="text-xs lg:text-lg leading-relaxed px-9 lg:px-0 lg:mb-0 -mb-14">No more guessing, you’ll know when your table is ready
                    when you get a notification from our app!</p>
                </div>
              </div>
            <div class="flex flex-col items-center pb-10 mx-auto mb-10 sm:flex-row lg:w-1/2">
              <div class="inline-flex items-center justify-center flex-shrink-0 w-20 h-20 rounded-ful sm:mr-10">
                  <div class="inline-flex items-center justify-center flex-shrink-0 sm:mr-10">
                      <img class=" h-12 lg:h-32 " src="{{ asset('images/beRewardedIcon.png') }}" alt="waitingLogo">
                  </div>
              </div>
              <div class="flex-grow mt-1 lg:mt-6 text-center sm:text-left sm:mt-0">
                  <h2 class="mb-2 lg:mb-1 text-lg font-semibold leading-none tracking-tighter lg:text-3xl"> Be Rewarded</h2>
                  <p class="text-xs lg:text-lg leading-relaxed px-9 lg:px-0">Stamp card for users that can be used for exclusive discounts and many more</p>
                </div>
              </div>
              <div class="flex flex-col items-center pb-10 mx-auto mb-10 sm:flex-row lg:w-1/2">
              <div class="inline-flex items-center justify-center flex-shrink-0 w-20 h-20 rounded-ful sm:mr-10">
                  <div class="inline-flex items-center justify-center flex-shrink-0 sm:mr-10">
                      <img class=" h-12 lg:h-32 " src="{{ asset('images/excitingPromosIcon.png') }}" alt="waitingLogo">
                  </div>
              </div>
              <div class="flex-grow mt-1 lg:mt-6 text-center sm:text-left sm:mt-0">
                  <h2 class="mb-2 lg:mb-1 text-lg font-semibold leading-none tracking-tighter lg:text-3xl"> Exciting Promos</h2>
                  <p class="text-xs lg:text-lg leading-relaxed px-9 lg:px-0">Be the first to hear about the exclusive promotions that our registered restaurants have to offer such as special lunch rates, holiday discounts, or exclusive promos for their anniversaries.</p>
                </div>
              </div>
          </div>
        </section>


        <section>
            <div class="grid lg:grid-cols-2 p-4 mt-10 lg:mt-32 pb-20  ">
                <div class="flex justify-center items-center">
                    <img class="w-32 md:w-52 lg:w-80" src="{{ asset('images/homepage.png') }}" alt="homepage">
                </div>
                <div class="font-Montserrat flex justify-center items-center flex-col">
                    <h1 class="my-5 lg:mb-4 lg:text-5xl text-lg text-center font-bold">Download The Samquicksal App Now!</h1>
                    <a href="#"><img class="w-2/5  mx-auto transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110" src="{{ asset('images/googlePlay.png') }}" alt="googlePlay"></a>
                </div>
            </div>
        </section> 
        </div>
    <footer class=" transition duration-500 ease-in-out transform bg-adminLoginTextColor ">
        <div class="flex flex-col flex-wrap justify-center p-2 lg:p-5 md:flex-row">
            <nav class="flex flex-wrap items-center justify-center w-full mx-auto mb-1 lg:mb-6 text-base nprd">
                <a href="#" class="px-1 lg:px-4 py-1 mr-1 text-xs lg:text-lg text-gray-50 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-submitButton ">About</a>
                <a href="#" class="px-1 lg:px-4 py-1 mr-1 text-xs lg:text-lg text-gray-50 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-submitButton ">Contact</a>
                <a href="#" class="px-1 lg:px-4 py-1 mr-1 text-xs lg:text-lg text-gray-50 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-submitButton ">Member</a>
            </nav>
            <span class="inline-flex justify-center w-full mx-auto -mb-5 lg:mt-2 mr-2 sm:ml-auto sm:mt-0">
                <a class="text-white hover:text-submitButton transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2">
                    <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="lg:w-6 w-4 lg:h-6 h-4" viewBox="0 0 24 24">
                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                    </svg>
                </a>
                
                <a class="ml-3 text-white hover:text-submitButton transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2">
                    <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="lg:w-6 w-4 lg:h-6 h-4" viewBox="0 0 24 24">
                        <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                    </svg>
                </a>
            
                <a class="ml-3 text-white hover:text-submitButton transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="lg:w-6 w-4 lg:h-6 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                </a>
            </span>
        </div>
        <div class="mx-auto mt-4 rounded-b-lg px-8 text-center w-full">
            <div class="inline-flex flex-col flex-wrap items-center px-2 lg:px-5 py-2 lg:py-6 mx-auto sm:flex-row ">
                <p class="  mx-auto text-xs lg:text-lg text-black">© 2021 </p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>