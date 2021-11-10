<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404 Error</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
<section class="font-Montserrat home-intro relative pt-16 pb-32 flex content-center items-center justify-center" style="min-height: 100vh;">
<div class="absolute top-0 w-full h-full bg-center bg-cover" style='background-image: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0)), url({{asset('images/restaurant1.png')}}); background-repeat: no-repeat; background-size: cover;" class="w-full h-full'>
            <span id="blackOverlay" class="w-full h-full absolute opacity-75 bg-black"></span>
          </div>
<div class="flex justify-center items-center flex-col relative text-white">
    <div class="mb-2 font-bold text-adminLoginTextColor">
        <h1 class="text-sm md:text-xl lg:text-2xl">404 ERROR</h1>
    </div>
    <div class="mb-1 md:mb-4">
        <spa class="text-2xl sm:text-4xl md:text-5xl lg:text-7xl">Uh oh! I think you're lost.</span>
    </div>
    <div class="text-adminLoginTextColor">
        <span  class="text-xxs sm:text-sm md:text-base lg:text-2xl font-bold">it looks like the page you're looking for doesn't exist</span>
    </div>
    <div class="mt-9">
        <button class="py-2 px-4 md:py-2 md:px-4 lg:py-3 lg:px-5 bg-submitButton opacity-75 rounded-2xl hover:bg-adminLoginTextColor transition duration-300 ease-in-out md:text-base text-sm"><a class="text-white" href="/">Go back Home</a></button>
    </div>
</div>
</section>

</body>
</html>