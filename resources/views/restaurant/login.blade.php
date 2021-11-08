<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restaurant | Login</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="login font-Montserrat">
    @if (session()->has('passwordUpdated'))
        <script>
            Swal.fire(
                'Your password has been updated',
                'You can now login again',
                'success'
            );
        </script>
    @endif
    <div class="box"></div>

    <div class="form mt-12">
        <div class="flex items-center justify-center mb-10">
           <a href="/" target="_blank"><img class="h-16 sm:h-16 md:h-16 lg:h-20 xl:h-20"src="{{ asset('images/samquicksalLogo.png') }}" alt="samquicksalLogo.png"></a> 
        </div>
        <section class="w-10/12 sm:w-8/12 md:w-9/12 md:h-32 lg:w-8/12 lg:h-2/6 xl:w-3/6 xl:h-4/6 grid md:grid-cols-12 lg:grid-cols-12 xl:grid-cols-12 mx-auto shadow-2xl ">
            <div class="md:col-span-5 lg:col-span-5 xl:col-span-5 ">
                <div style="background-image: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0)), url({{asset('images/login-left-bg.png')}}); background-repeat: no-repeat; background-size: cover;" class=" md:h-full lg:h-full xl:h-full rounded-l-xl"></div>
            </div>
            <div class="grid md:col-span-7 lg:col-span-7 xl:col-span-7  items-center py-12 px-16 bg-gradient-to-b from-white to-headerBgColor md:rounded-r-xl lg:rounded-r-xl xl:rounded-r-xl">
                <form action="/restaurant/login" method="POST">
                    @csrf
                    <h1 class="flex justify-center text-login text-lg sm:text-xl md:text-lg lg:text-2xl xl:text-2xl text-adminLoginTextColor">Restaurant Login</h1>
                    <div class="grid grid-col-2 my-8 lg:text-sm xl:text-sm">
                        <label class="text-loginLandingPage text-xs sm:text-sm md:text-sm lg:text-sm xl:text-base">Username</label>
                        <div class="w-full relative">
                            <i class="fas fa-user absolute text-xs sm:text-xs md:text-xs lg:text-sm xl:text-base px-2 py-1.5 sm:py-2 sm:px-2 md:px-3 md:py-3 lg:px-3 lg:py-3 xl:px-3 xl:py-3 text-loginLandingPage"></i>
                            <input type="text" name="username" class="w-full pl-8 pr-3 md:py-1 lg:py-2 xl:py-2 rounded-md border-multiStepBoxBorder {{($errors->has('username') ? 'border-red-600' : 'border-loginLandingPage')}} focus:outline-none" autocomplete="off">
                        </div>
                        <span class="text-red-600 h-4 mt-1 text-xxs lg:text-sm xl:text-base">@error('username'){{ "Username is required" }}@enderror</span>
                        
                        <label class="text-loginLandingPage md:mt-2 lg:mt-5 xl:mt-5 md:text-sm text-xs sm:text-sm lg:text-sm xl:text-base">Password</label>
                        <div class="w-full relative">
                            <i class="fas fa-lock absolute text-xs sm:text-xs md:text-xs lg:text-sm xl:text-base px-2 py-1.5 sm:py-2 sm:px-2 md:px-3 md:py-3 lg:px-3 lg:py-3 xl:px-3 xl:py-3 text-loginLandingPage"></i>
                        <input type="password" name="password" class="w-full pl-8 pr-3 md:py-1 lg:py-2 xl:py-2 rounded-md border-multiStepBoxBorder {{($errors->has('password') ? 'border-red-600' : 'border-loginLandingPage')}} focus:outline-none" autocomplete="off">
                        </div>

                        <span class="text-red-600 h-4 mt-1 text-xxs lg:text-sm xl:text-base">
                            @error('password'){{ "Password is required" }}@enderror
                            
                            @if (session()->has('invalidPassword'))
                                <h3>{{ session('invalidPassword') }}</h3>
                            @endif
                        </span>
                        <div class="col-span-full text-right lg:mt-2">
                            <a href="/restaurant/login/forgot-password" class=" xl:text-sm lg:text-xxs md:text-xxs sm:text-xs text-xxs text-loginLandingPage underline hover:text-gray-100 transition duration-200 ease-in-out">Forgot Password?</a>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button class="text-white bg-headerActiveTextColor rounded-full w-36 h-7 sm:w-36 sm:h-7 md:w-28 md:h-7 lg:w-36 lg:h-8 xl:h-10 xl:w-56 mb-5 text-xs sm:text-sm  md:text-xs lg:text-xs xl:text-xs font-Roboto hover:bg-btnHoverColor transition duration-300 ease-in-out hover:font-bold">Login</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</body>
</html>