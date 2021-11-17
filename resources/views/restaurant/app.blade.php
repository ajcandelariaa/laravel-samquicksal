<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restaurant | {{ $title }}</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="font-Roboto">
    <header class="bg-adminLoginTextColor">
        <div class="w-full mx-auto py-3 px-5">
            <div class="grid grid-cols-2 items-center">
                <div class="justify-self-start">
                    <img src="{{ asset('images/samquicksalLogo.png') }}" class="w-10" alt="samquicksalLogo">
                </div>
                <div class="logoutBtn justify-self-end pr-5">
                    <a href="/restaurant/logout" class=" text-white hover:underline hover:text-gray-300 transition duration-200 ease-in-out">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="flex flex-column">
            <div class="bg-sideBarBgColor min-h-screen w-96">
                <div class="grid items-center mt-8 {{ (request()->is('restaurant/dashboard*')) ? 'text-headerActiveTextColor bg-black' : 'text-white hover:bg-sideBarHoverBgColor' }}">
                    <a href="/restaurant/dashboard" class="pt-3 pb-3"><i class="fas fa-th-large w-10 ml-8"></i>Dashboard</a>
                </div>
                <div class="grid items-center mt-4 {{ (request()->is('restaurant/live-transaction*')) ? 'text-headerActiveTextColor bg-black' : 'text-white hover:bg-sideBarHoverBgColor' }}">
                    <a href="/restaurant/live-transaction/customer-booking/queue" class="pt-3 pb-3 hover:text-gray-300"><i class="fas fa-podcast w-10 ml-8 hover:text-gray-300"></i>Live Transactions</a>
                </div>
                <div class="grid items-center mt-4 {{ (request()->is('restaurant/transaction-history*')) ? 'text-headerActiveTextColor bg-black' : 'text-white hover:bg-sideBarHoverBgColor' }}">
                    <a href="/restaurant/transaction-history/cancelled/queue" class="pt-3 pb-3 hover:text-gray-300"><i class="fas fa-history w-10 ml-8 hover:text-gray-300"></i>Transaction History</a>
                </div>
                <div class="grid items-center mt-4 {{ (request()->is('restaurant/stamp-offenses*')) ? 'text-headerActiveTextColor bg-black' : 'text-white hover:bg-sideBarHoverBgColor' }}">
                    <a href="/restaurant/stamp-offenses/stamp-history" class="pt-3 pb-3 hover:text-gray-300"><i class="fas fa-stamp w-10 ml-8 hover:text-gray-300"></i>Stamp & Offenses</a>
                </div>
                <div class="grid items-center mt-4 {{ (request()->is('restaurant/manage-restaurant*')) ? 'text-headerActiveTextColor bg-black' : 'text-white hover:bg-sideBarHoverBgColor' }}">
                    <a href="/restaurant/manage-restaurant/about/restaurant-information" class="pt-3 pb-3 hover:text-gray-300"><i class="fas fa-file-signature w-10 ml-8 hover:text-gray-300"></i>Manage Restaurant</a>
                </div>
            </div>
            <div class="bg-gray-200 w-full">
                @yield('content')
            </div>
        </div>
    </section>
</body>
</html>