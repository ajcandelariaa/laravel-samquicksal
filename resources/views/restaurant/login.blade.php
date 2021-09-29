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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="login">
    <div class="box"></div>

    <div class="form mt-12">
        <header class="flex items-center justify-center mb-6">
            <img class="h-20"src="{{ asset('images/samquicksalLogo.png') }}" alt="samquicksalLogo.png">
        </header>

        <section class="w-3/6 h-4/6 grid grid-cols-12 mx-auto shadow-xl">
            <div class="col-span-5 relative">
                <div style="background-image: linear-gradient(rgba(229, 138, 138, 0.65), rgba(229, 138, 138, 0.65)), url({{asset('images/samgyeopsal.jpg')}}); background-repeat: no-repeat; background-size: cover;" class="h-full"></div>
                <div class="absolute top-0">
                    <span class="uppercase text-white">samquicksal</span>
                </div>
            </div>
            
            <div class="py-12 px-16 col-span-7 bg-gradient-to-b from-white to-headerBgColor">
                <form action="/restaurant/login" method="POST">
                    @csrf
                    <h1 class="flex justify-center text-login text-2xl text-adminLoginTextColor">Login</h1>
                    <div class="grid grid-col-2 my-8 text-sm">
                        <label class="{{($errors->has('username') ? 'text-red-600' : 'text-adminLoginTextColor')}}">Username</label>
                        <input type="text" name="username" style="font-family: 'Font Awesome 5 Free'; font-weight: 700;" placeholder="&#xf406;" class="px-3 py-1 rounded-md border-2 {{($errors->has('username') ? 'border-red-600' : 'border-adminLoginTextColor')}} focus:outline-none" autocomplete="off">
                        <div class="text-red-600 h-4">@error('username'){{ $message }}@enderror</div>
                        
                        <label class="{{($errors->has('username') ? 'text-red-600' : 'text-adminLoginTextColor')}} mt-3">Password</label>
                        <input type="password" name="password"style="font-family: 'Font Awesome 5 Free'; font-weight: 700;" placeholder="&#xf023;" class="px-3 py-1 rounded-md  border-2 {{($errors->has('username') ? 'border-red-600' : 'border-adminLoginTextColor')}} focus:outline-none" autocomplete="off"">
                        <div class="text-red-600 h-4">
                            @error('password'){{ $message }}@enderror
                            @if (session()->has('notExist'))
                                <h3>{{ session('notExist') }}</h3>
                            @endif
                            
                            @if (session()->has('invalidPassword'))
                                <h3>{{ session('invalidPassword') }}</h3>
                            @endif
                        </div>
                    </div>
                        <!--Button-->
                    <div class="flex justify-center items-center">
                        <button class="text-white bg-headerActiveTextColor rounded-full h-10 w-56 mb-5 text-sm">Login</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</body>
</html>