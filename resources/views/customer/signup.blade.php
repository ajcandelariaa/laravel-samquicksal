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
    <title>Signup | Samquicksal</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
</head>
<body class="antialiased ">
    <div class="home-intro relative pt-16 pb-32 flex content-center items-center justify-center bg-black" style="min-height: 100vh;">
        <div class="absolute top-0 w-full h-full bg-center bg-cover bg-black" style='background-image: url({{asset('images/samgyupGrill.png')}}); background-repeat: no-repeat; background-size: cover; filter: blur(6px);" class="w-full h-full'>
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
    <div class="flex flex-col lg:flex-row justify-center items-center xl:-mb-24">
        <div class="flex justify-center items-center lg:hidden font-Montserrat font-bold">
            <h1 class="text-white text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl mb-16">Create
            <span class="text-submitButton ">an Account</span> </h1> 
        </div>
      <div class="rounded-xl bg-white px-5 py-7 xl:py-7 lg:py-8 md:py-8 sm:py-6 xl:px-20 lg:px-12 md:px-10 sm:px-12 xl:ml-52 lg:ml-24 ">
          <form action="" method="POST">
            @csrf
          <div class="flex flex-col justify-center items-center">
              <h1 class="mx-auto font-Montserrat text-lg sm:text-lg md:text-xl lg:text-xl xl:text-2xl font-bold pb-10 sm:pb-10 md:pb-10 lg:pb-10 xl:pb-10 text-submitButton">Sign Up</h1>
              <input class="mb-2 sm:mb-2 md:mb-2 lg:mb-2 xl:mb-3 w-56 sm:w-72 md:w-68 lg:w-80 xl:w-96 pl-3 pr-3 sm:py-1 md:py-1 lg:py-2 xl:py-2 py-1 rounded-md border-multiStepBoxBorder" type="text" name="name" value="{{ old('name') }}" placeholder="Name">
              @error('name')<span class="text-red-600 text-left">{{ $message }}</span>@enderror

              <input class="mb-2 sm:mb-2 md:mb-2 lg:mb-2 xl:mb-3 w-56 sm:w-72 md:w-68 lg:w-80 xl:w-96 pl-3 pr-3 sm:py-1 md:py-1 lg:py-2 xl:py-2 py-1 rounded-md border-multiStepBoxBorder" type="text" name="emailAddress" id="" value="{{ old('emailAddress') }}" placeholder="Email Address">
              @error('emailAddress')<span class="text-red-600 text-left">{{ $message }}</span>@enderror

              <input  class="mb-2 sm:mb-2 md:mb-2 lg:mb-2 xl:mb-3 w-56 sm:w-72 md:w-68 lg:w-80 xl:w-96 pl-3 pr-3 sm:py-1 md:py-1 lg:py-2 xl:py-2 py-1 rounded-md border-multiStepBoxBorder"type="number" name="contactNumber" value="{{ old('contactNumber') }}" placeholder="Contact Number">
              @error('contactNumber')<span class="text-red-600 text-left">{{ $message }}</span>@enderror

              <input class="mb-2 sm:mb-2 md:mb-2 lg:mb-2 xl:mb-3 w-56 sm:w-72 md:w-68 lg:w-80 xl:w-96 pl-3 pr-3 sm:py-1 md:py-1 lg:py-2 xl:py-2 py-1 rounded-md border-multiStepBoxBorder" type="password" name="password" placeholder="Password">
              @error('password')<span class="text-red-600 text-left">{{ $message }}</span>@enderror

              <input class="mb-2 sm:mb-2 md:mb-2 lg:mb-2 xl:mb-3 w-56 sm:w-72 md:w-68 lg:w-80 xl:w-96 pl-3 pr-3 sm:py-1 md:py-1 lg:py-2 xl:py-2 py-1 rounded-md border-multiStepBoxBorder" type="password" name="confirmPassword" placeholder="Confirm Password">
              @error('confirmPassword')<span class="text-red-600 text-left">{{ $message }}</span>@enderror

          </div>
          <div class="flex flex-col justify-center items-center">
              <button type="submit" class="mx-autofont-Roboto text-xs md:text-xs lg:text-sm rounded-full text-white bg-submitButton border-white border-2 py-1 md:py-1 lg:py-1 xl:py-2 xl:w-8/12 lg:w-6/12 md:w-6/12 sm:w-6/12 w-7/12 xl:mt-5 lg:mt-6 md:mt-5 sm:mt-5 mt-3 mb-3 hover:bg-adminLoginTextColor hover:text-white transition duration-200 ease-in-out">Sign Up</button>
              <a class="text-headerBgColor text-sm font-Roboto xl:mb-3 underline" href="/customer/login">Already have an account?</a>
          </div>
          
        </form>
      </div>
      <div class="flex flex-col mx-auto justify-center items-center">
          <div class="hidden lg:contents">
              <img class="lg:w-7/12 xl:w-5/12 mx-auto" src="{{ asset('images/girlBoyBlack.png') }}" alt="girlBoyBlack">
          </div>
          <div class="hidden font-Montserrat font-bold lg:inline-flex lg:mt-12 xl:mt-12 ">
              <h1 class="text-white lg:text-5xl xl:text-6xl">Create
              <span class="text-submitButton ">an Account</span> </h1> 
          </div>
      </div>
    </div>
</section>

  <script>
    function toggleNavbar(collapseID) {
      document.getElementById(collapseID).classList.toggle("hidden");
      document.getElementById(collapseID).classList.toggle("block");
    }
  </script>
</body>
</html>