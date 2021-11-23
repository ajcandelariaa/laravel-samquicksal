<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer | Forgot Password</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    <script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class=" font-Montserrat">
    @if (session()->has('emailSent'))
        <script>
            Swal.fire(
                'We have emailed your password reset link',
                '',
                'success'
            );
        </script>
    @endif
    <header class="top-0 w-full flex flex-wrap items-center justify-between header_2 ">
        <div class="container px-4 mx-auto flex flex-wrap items-center justify-between">
          <div class="w-full relative flex justify-between lg:w-auto lg:static lg:block lg:justify-start">
            <a class="text-sm font-bold leading-relaxed inline-block mr-4 whitespace-nowrap uppercase text-white" href="/"><img class="w-36 xl:w-36" src="{{ asset('images/samquicksalWithWords.png') }}" alt="samquicksalWithWords"></a>
            <button class="cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none" type="button" onclick="toggleNavbar('example-collapse-navbar')">
              <i class="text-submitButton fas fa-bars "></i>
            </button>
          </div>
          <nav class="lg:flex flex-grow items-center bg-black lg:bg-transparent lg:shadow-none hidden" id="example-collapse-navbar">
            <ul class="flex flex-col sm:flex-row lg:flex-row list-none lg:ml-auto font-Montserrat">
              <li class="flex items-center">
                  <input class="mx-auto lg:mb-0 sm:mr-3 w-56 sm:w-56 md:w-56 lg:w-52 xl:w-56 pl-3 pr-3 sm:py-1 md:py-1 lg:py-2 xl:py-2 py-1 rounded-md border-multiStepBoxBorder mb-2" type="text"  placeholder="Username">
                  <span class="text-red-600 h-4 mt-1 text-xxs lg:text-sm xl:text-base">@error('username'){{ "Username is required" }}@enderror</span>
                
              </li>
              <li class="flex items-center">
                <input class="mx-auto lg:mb-0 sm:mr-3 w-56 sm:w-56 md:w-56 lg:w-56 xl:w-52 pl-3 pr-3 sm:py-1 md:py-1 lg:py-2 xl:py-2 py-1 rounded-md border-multiStepBoxBorder mb-2" type="password" name="" id="" placeholder="Password">
               
            
              </li>
              <li class="flex items-center">
                <a class="mx-auto sm:mx-2 mb-2 lg:mb-0 cursor-pointer text-white rounded-full bg-darkerSubmitButton hover:bg-white hover:text-darkerSubmitButton transition duration-200 ease-in-out px-7 py-2 lg:py-2 flex items-center text-xs uppercase font-bold  "  href="/customer/profile" target="_blank">
                  <p class="md:hidden sm:hidden hidden xl:contents">Login</p>
                  <span class="xl:hidden inline-block">Login</span></a>
              </li>
            </ul>
          </nav>
        </div>
      </header>
    <div class="box"></div>
    <div class="form mt-60 relative">
        <div class="flex items-center justify-center mb-10 ">
            <img class="w-52 sm:w-60 md:w-72 lg:w-76 absolute bottom-68"src="{{ asset('images/forgot.png') }}" alt="forgot.png">
        </div>

        <section class="relative">
            <div class="mx-auto w-9/12 sm:w-6/12 md:w-6/12 lg:w-4/12 py-12 px-16 rounded-xl relative flex justify-center items-center shadow-2xl">
                <form action="/restaurant/login/forgot-password" method="POST">
                    @csrf
                    <h1 class="flex justify-center text-login text-xl text-submitButton mt-1">Forgot Password</h1>
                    <div class="grid grid-col-2 my-8 text-sm">
                        <label class="text-loginLandingPage">Email Address</label>
                        <div class="w-full relative">
                            <i class="fas fa-envelope absolute px-3 py-3 text-loginLandingPage"></i>
                            <input type="text" name="emailAddress" class="md:w-72 lg:w-80 pl-8 pr-3 py-2 rounded-md border-multiStepBoxBorder {{($errors->has('emailAddress') ? 'border-red-600' : 'border-loginLandingPage')}} focus:outline-none" autocomplete="off">
                        </div>
                        <span class="text-red-600 h-4 mt-1">
                            @error('emailAddress'){{ $message }}@enderror
                            
                            @if (session()->has('emailNotExist'))
                                It seems like your email address is not registered on our system.
                            @endif
                        </span>
                            
                    </div>
                
                    <div class="text-center">
                        <button class="text-white bg-headerActiveTextColor rounded-full h-10 w-56 mb-5 text-xs font-Roboto hover:bg-btnHoverColor transition duration-200 ease-in-out">Send Password Link</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <footer>
        <div>

        </div>
    </footer>


      <script>
        function toggleNavbar(collapseID) {
          document.getElementById(collapseID).classList.toggle("hidden");
          document.getElementById(collapseID).classList.toggle("block");
        }
      </script>
</body>
</html>