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
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    <title>Login | Samquicksal</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
</head>
<body class="antialiased ">
    <div class="home-intro relative pt-16 pb-32 flex content-center items-center justify-center bg-black" style="min-height: 100vh;">
        <div class="absolute top-0 w-full h-full bg-center bg-cover bg-black" style='background-image: url({{asset('images/samgyupGrill.png')}}); background-repeat: no-repeat; background-size: cover; filter: blur(6px); class="w-full h-full'>
          <span id="blackOverlay" class="w-full h-full absolute opacity-80 bg-black bg-gradient-to-t from-adminLoginTextColor"></span>
        </div>
  <header class="top-0 z-50 w-full flex flex-wrap items-center justify-between px-2 header_1">
  <div class="container px-4 mx-auto flex flex-wrap items-center justify-between">
    <div class="w-full relative flex justify-between lg:w-auto lg:static lg:block lg:justify-start">
      <a class="text-sm font-bold leading-relaxed inline-block mr-4 py-2 whitespace-nowrap uppercase text-white" href="/"><img class="w-36 lg:w-40 xxl:w-96" src="{{ asset('images/samquicksalWithWords.png') }}" alt="samquicksalWithWords"></a>

      <button class="cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none" type="button" onclick="toggleNavbar('example-collapse-navbar')">
        <i class="text-submitButton fas fa-bars "></i>
      </button>
    </div>
    <nav class="lg:flex flex-grow items-center bg-white lg:bg-transparent lg:shadow-none hidden" id="example-collapse-navbar">
      <ul class="flex flex-col lg:flex-row list-none lg:ml-auto font-Montserrat">
        <li class="flex items-center">
          <a class="nav__link cursor-pointer text-submitButton px-7 py-4 lg:py-2 flex items-center lg:text-base uppercase font-bold" href="/restaurant" target="_blank">
          <p class="md:hidden sm:hidden hidden xl:contents">Be Our Partner</p>
              <span class="xl:hidden inline-block ml-2">Be Our Partner</span><a>
        </li>
      </ul>
    </nav>
  </div>
</header>

<section class="relative">
    <form action="/customer/login" method="POST">
      @csrf
      <div class="flex flex-col rounded-xl bg-white py-10 px-8 sm:px-16 md:px-24 sm:py-10 md:py-12 lg:px-24 lg:py-16 xl:px-32 xl:py-20 xl:mt-5 ">
          <div class="mx-auto flex flex-col justify-center items-center">
              <span class="mx-auto text-Montserrat font-medimu text-xl sm:text-2xl md:text-2xl lg:text-3xl xl:text-3xl text-submitButton mb-10 sm:mb-12 md:mb-12 lg:mb-16 xl:mb-16">Login</span>
              <input class="w-56 sm:w-80 md:w-80 lg:w-96 xl:w-96 pl-3 pr-3 sm:py-1 md:py-1 lg:py-2 xl:py-2 py-1 rounded-md border-multiStepBoxBorder" type="text" name="emailAddress" placeholder="Email Address">
              @error('emailAddress')<span class="text-red-600 h-4 mt-1 text-left">{{ $message }}</span>@enderror

              <input class="w-56 sm:w-80 md:w-80 lg:w-96 mt-4 sm:mt-4 md:mt-4 lg:mt-5 xl:mt-7 xl:w-96 pl-3 pr-3  py-1 sm:py-1 md:py-1 lg:py-2 xl:py-2 rounded-md border-multiStepBoxBorder" type="password" name="password" placeholder="Password">
              @error('password')<span class="text-red-600 h-4 mt-1 text-left">{{ $message }}</span>@enderror
          </div>
          <div class=" flex justify-end lg:mt-2">
              <a href="#" class=" md:mt-2 xl:text-sm lg:text-xs md:text-xs sm:text-xs text-xs text-loginLandingPage underline hover:text-gray-700 transition duration-200 ease-in-out">Forgot Password?</a>
          </div>

          <div class="flex flex-col justify-center items-center">
            <button type="submit"class="text-center mx-auto font-Roboto text-xs md:text-xs lg:text-sm rounded-full text-white bg-submitButton border-white border-2 py-1 md:py-1 lg:py-1 xl:py-2 xl:w-8/12 lg:w-6/12 md:w-6/12 sm:w-6/12 w-7/12 xl:mt-5 xl:mb-3 lg:mt-6 lg:mb-3 md:mt-5 sm:mt-5 md:mb-3 sm:mb-3 mt-3 mb-3 hover:bg-adminLoginTextColor hover:text-white transition duration-200 ease-in-out">Login</button>

            <a class="text-center mx-auto font-Roboto text-xs md:text-xs lg:text-sm rounded-full text-submitButton bg-white border-submitButton border-2 py-1 md:py-1 lg:py-1 xl:py-2 xl:w-8/12 lg:w-6/12 md:w-6/12 sm:w-6/12 w-7/12 xl:mb-3 sm:mb-4 hover:bg-adminLoginTextColor hover:text-white transition duration-200 ease-in-out"" href="/customer/signup">Sign up</a>
          </div>
      </div>
    </form>
</section>

<script>
  function toggleNavbar(collapseID) {
    document.getElementById(collapseID).classList.toggle("hidden");
    document.getElementById(collapseID).classList.toggle("block");
  }
</script>
</body>
</html>