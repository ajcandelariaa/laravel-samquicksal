<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | {{ $title }}</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="font-Roboto">
    <header class="bg-headerBgColor">
        <div class="w-full mx-auto py-3 px-5">
            <div class="grid grid-cols-2 items-center">
                <div class="justify-self-start">
                    <img src="{{ asset('images/samquicksalLogo.png') }}" class="w-10" alt="samquicksalLogo">
                </div>
                <div class="logoutBtn justify-self-end pr-5">
                    <a href="/admin/logout" class="text-white hover:underline">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="flex flex-column">
            <div class="bg-sideBarBgColor min-h-screen w-96">
                <div class="grid items-center mt-8 {{ (request()->is('admin/dashboard*')) ? 'text-headerActiveTextColor bg-black' : 'text-white hover:bg-sideBarHoverBgColor' }}">
                    <a href="/admin/dashboard" class="pt-3 pb-3 font-bold"><i class="fas fa-th-large w-10 ml-8"></i>Dashboard</a>
                </div>
                <div class="grid items-center mt-4 {{ (request()->is('admin/restaurant-applicants*')) ? 'text-headerActiveTextColor bg-black' : 'text-white hover:bg-sideBarHoverBgColor' }}">
                    <a href="/admin/restaurant-applicants" class="pt-3 pb-3 font-bold"><i class="fas fa-file-signature w-10 ml-8"></i>Restaurant Applicants</a>
                </div>
                <div class="grid items-center mt-4 {{ (request()->is('admin/restaurant-accounts*')) ? 'text-headerActiveTextColor bg-black' : 'text-white hover:bg-sideBarHoverBgColor' }}">
                    <a href="/admin/restaurant-accounts" class="pt-3 pb-3 font-bold"><i class="fas fa-user-tie w-10 ml-8"></i>Restaurant Owners</a>
                </div>
                <div class="grid items-center mt-4 {{ (request()->is('admin/customer-accounts*')) ? 'text-headerActiveTextColor bg-black' : 'text-white hover:bg-sideBarHoverBgColor' }}">
                    <a href="/admin/customer-accounts" class="pt-3 pb-3 font-bold"><i class="fas fa-users w-10 ml-8"></i>Customers</a>
                </div>
            </div>
            <div class="bg-adminBgColor w-full">
                @yield('content')
            </div>
        </div>
    </section>
</body>
</html>