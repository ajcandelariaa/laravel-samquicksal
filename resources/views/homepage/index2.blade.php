<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Samquicksal</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
</head>
<body>
  <header class="bg-adminLoginTextColor flex items-center flex-wrap font-Roboto">
            <!--LOGO-->
            <div>
                <img class="w-12 ml-3 lg:ml-10 my-3" src="{{ asset('images/samquicksalLogo.png') }}" alt="samquicksalLogo">
            </div>
            <!--Navigator-->
                <button class="text-gray-200 inline-flex mr-1 p-3 rounded lg:hidden ml-auto transition duration-200 nav-toggler  " data-target="#navigation">
                    <svg class="h-8" xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                      </svg>
                </button>
            <!--Start-Login & Signup-->
            <div class="hidden top-navbar w-full lg:inline-flex lg:flex-grow lg:w-auto" id="navigation">
                <div class="lg:inline-flex lg:flex-row lg:ml-auto flex flex-col">
                    <a href="#" class="lg:inline-flex lg:w-auto px-3 py-2 mx-5 rounded text-gray-200 hover:text-white transition duration-200 hover:bg-submitButton">
                        <span>Be Our Partner</span>
                    </a>
                    <a href="#" class="lg:inline-flex lg:w-auto px-3 py-2 rounded text-gray-200 hover:text-white mx-5 hover:font-bold transition duration-200 hover:bg-submitButton">
                        <span>Login</span></a>
                    <a href="#" class="lg:inline-flex lg:w-auto px-3 py-2 rounded text-gray-200 hover:text-white mx-5 mr-8 hover:font-bold transition duration-200 hover:bg-submitButton">
                        <span>Signup</span>
                    </a>
                </div>
            </div>
        </header>
        <!-- Carousel-->
            <section>
                <div class="w-full h-adminLoginBoxH bg-cover bg-center" style="background-image: url({{ asset('images/koreanBeefFood.jpg') }})"></div>
            </section>
        <!--DINE IN btn-->
        <section>
            <div class="bg-white grid lg:grid-cols-2 p-16 lg:pl-20 items-center justify-center">
                <div class="lg:ml-5 mb-5 text-center lg:text-left w-7/12">
                    <h1 class="lg:text-6xl text-2xl font-Montserrat ">Treat Yourself with Samquicksal!</h1>
                    <p class="font-Roboto mt-1 lg:mt-2 lg:ml-1 text-sm">Your Quick Way to Samgyup!</p>
                </div>
                <div class="mx-auto font-Roboto border-submitButton text-submitButton border-2 rounded-full  uppercase lg:py-4 lg:px-20 px-6 py-1 lg:text-xl text-sm cursor-pointer  tracking-wider hover:bg-submitButton hover:text-gray-50 shadow-inner hover:shadow-xl transition duration-200 ">
                    <a href="#">
                        Dine in
                    </a>
                </div>
            </div>
        </section>
        <!--Blank-->
      <section>
       </section>
        <!--Mission&Vision-->
        <section  class="grid lg:grid-cols-2 lg:py-36 relative">
        <!--Logo Left Side-->
          <div class="mx-auto w- lg:w-4/6 ">
            <img class="lg:my-20 mt-8 " src="{{ asset('images/samquicksalLogo.png') }}" alt="samquicksalLogo">
          </div>
        <!--Mission&Vision-->
          <div class="z-10 text-submitButton font-Montserrat font-bold text-sm lg:text-xl px-10 mt-3 lg:p-16">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam quis enim lobortis scelerisque fermentum dui faucibus in ornare. A arcu cursus vitae congue mauris rhoncus aenean. Elementum nisi quis eleifend quam adipiscing vitae. Nam aliquam sem et tortor consequat id porta. Varius duis at consectetur lorem. Malesuada proin libero nunc consequat interdum varius sit. Dui id ornare arcu odio ut sem nulla pharetra diam. Eu lobortis elementum nibh tellus molestie nunc non. Eu ultrices vitae auctor eu augue ut. Et molestie ac feugiat sed lectus. Habitant morbi tristique senectus et netus et malesuada fames ac. Ultricies lacus sed turpis tincidunt.</p>
            <p class="mt-2 lg:mt-4">Commodo elit at imperdiet dui. Cursus risus at ultrices mi tempus. Etiam non quam lacus suspendisse faucibus interdum posuere. Massa ultricies mi quis hendrerit. Proin sed libero enim sed. Euismod in pellentesque massa placerat duis ultricies lacus. </p>
        <!--rounded rectangle-->
          </div>
          <div class="hidden overflow-hidden absolute md:block rounded-lg bg-indigo-200 top-80 h-80 w-6/12  right-0 z-0">
          
          </div>
        </section>
        <!--No Long wait section-->
        <section class="font-Montserrat">
            <div class="container flex flex-col items-start px-5 py-16 mx-auto lg:items-center md:flex-row lg:px-28">
              <!--RED BOX-->
          <div class="mx-auto">
            <div class="bg-submitButton text-white rounded-xl ">
              <div class="flex justify-center">
                <h1 class="uppercase font-black text-4xl mr-2 my-4">no</h1>
              <div class="flex flex-col mr-2 mt-4">
                <p>Long Wait Hours!</p>
                <p>More Falling in Line!</p>
              </div>
            </div>
        <!--Image-->
              <div class="flex justify-center items-center">
                <img class="w-2/3 rounded-t-xl shadow-3xl" src="{{ asset('images/muhanUnilimitedSamgyupsal.png') }}" alt="muhanUnilimitedSamgyupsal">
              </div>
            </div>
          </div>
              <div class="flex flex-col items-start text-left lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16">
                <h2 class="mb-8 font-semibold tracking-widest text-black uppercase text-xl  lg:text-sm ">Samquicksal</h2>
                <h1 class="mb-8 text-2xl font-bold tracking-tighter text-left text-black title-font">Samquicksal has Booking and Queueing so you don't have to wait in line anymore.</h1>
                <p class="mb-8 text-base leading-relaxed text-left text-blueGray-700">In the mood for samgyupsal today? Use our queueing feature to queue yourself,friends and family in! It also lets you know how many customers are in the restaurant so you can go ahead and select another one if you're in a rush! See its Samquick!</p>
                <p class="mb-8 text-base leading-relaxed text-left text-blueGray-700">Looking to get that samgyup fix in advance? Use the booking feature to reserve your slot ahead of the others to ensure you get your table and know when to get there!</p>
                <p class="flex mb-2 text-blueGray-600"><span class="inline-flex items-center justify-center flex-shrink-0 w-6 h-6 mr-2 rounded-full">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                      <path fill="none" d="M0 0h24v24H0z"></path>
                      <path d="M10 15.172l9.192-9.193 1.415 1.414L10 18l-6.364-6.364 1.414-1.414z"></path>
                    </svg></span>No more long wait hours</p>
                <p class="flex mb-6 text-blueGray-600"><span class="inline-flex items-center justify-center flex-shrink-0 w-6 h-6 mr-2 rounded-full">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                      <path fill="none" d="M0 0h24v24H0z"></path>
                      <path d="M10 15.172l9.192-9.193 1.415 1.414L10 18l-6.364-6.364 1.414-1.414z"></path>
                    </svg></span>No more falling in line</p>
              </div>
            </div>
          </section>
          <!--Download now-->
        <section>
          <div class="grid lg:grid-cols-2 p-4 mt-32">
            <div class="flex justify-center items-center">
              <img class="w-2/5 lg:w-80" src="{{ asset('images/homepage.png') }}" alt="homepage">
            </div>
            <div class="font-Montserrat flex justify-center items-center font-bold flex-col">
              <p class="mb-4 lg:text-3xl text-lg text-center">Download The Samquicksal 
                App Now!</p>
                <a href="#"><img class="w-2/5  mx-auto" src="{{ asset('images/googlePlay.png') }}" alt="googlePlay"></a>
            </div>
          </div>
        </section> 
      </section> 
        
      {{-- <div class=" items-center ">
        <footer class="text-blueGray-700 transition duration-500 ease-in-out transform bg-white border ">
          <div class="flex flex-col flex-wrap justify-center p-5 md:flex-row">
            <nav class="flex flex-wrap items-center justify-center w-full mx-auto mb-6 text-base nprd">
              <a href="#" class="px-4 py-1 mr-1 text-base text-blueGray-500 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-black ">About</a>
              <a href="#" class="px-4 py-1 mr-1 text-base text-blueGray-500 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-black ">Contact</a>
              <a href="#" class="px-4 py-1 mr-1 text-base text-blueGray-500 transition duration-500 ease-in-out transform rounded-md focus:shadow-outline focus:outline-none focus:ring-2 ring-offset-current ring-offset-2 hover:text-black ">Member</a>
            </nav>
            <span class="inline-flex justify-center w-full mx-auto mt-2 mr-2 sm:ml-auto sm:mt-0">
              <a class="text-blue-500 hover:text-submitButton">
                <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                  <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                </svg>
              </a>
              <a class="ml-3 text-blue-500 hover:text-submitButton">
                <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                  <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z">
                  </path>
                </svg>
              </a>
              <a class="ml-3 text-blue-500 hover:text-submitButton">
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                  <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                  <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                </svg>
              </a>
              <a class="ml-3 text-blue-500 hover:text-submitButton">
                <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="w-5 h-5" viewBox="0 0 24 24">
                  <path stroke="none" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z">
                  </path>
                  <circle cx="4" cy="4" r="2" stroke="none"></circle>
                </svg>
              </a>
            </span>
          </div>
          <div class="w-full px-8 mt-4 rounded-b-lg bg-blueGray-50 mx-auto border-red-500">
           <div class=" container inline-flex flex-col flex-wrap justify-center items-center px-5 py-6 mx-auto sm:flex-row">
              <p class=" text-sm text-center text-black sm:text-left "> © 2021 </p>
            </div>
          </div>
        </footer>
      </div> --}}
      

    </main> 
    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>