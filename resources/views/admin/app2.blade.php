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
            {{-- w-96 384px --}}
            <div class="bg-sideBarBgColor min-h-screen w-16">
                <div class="grid items-center mt-8 text-white {{ (request()->is('admin/dashboard*')) ? 'relative bg-black' : 'hover:bg-sideBarHoverBgColor' }}">
                    <div class="{{ (request()->is('admin/dashboard*')) ? 'h-full w-1 bg-headerActiveTextColor absolute left-0' : '' }}"></div>
                    <a href="/admin/dashboard" class="pt-3 pb-3 text-center">
                        <i class="fas fa-th-large"></i>
                    </a>
                </div>
                <div class="grid items-center mt-4 text-white {{ (request()->is('admin/restaurant-applicants*')) ? 'relative bg-black' : 'hover:bg-sideBarHoverBgColor' }}">
                    <div class="{{ (request()->is('admin/restaurant-applicants*')) ? 'h-full w-1 bg-headerActiveTextColor absolute left-0' : '' }}"></div>
                    <a href="/admin/restaurant-applicants" class="pt-3 pb-3 text-center">
                        <i class="fas fa-file-signature"></i>
                    </a>
                </div>
                <div class="grid items-center mt-4 text-white {{ (request()->is('admin/restaurant-accounts*')) ? 'relative bg-black' : 'hover:bg-sideBarHoverBgColor' }}">
                    <div class="{{ (request()->is('admin/restaurant-accounts*')) ? 'h-full w-1 bg-headerActiveTextColor absolute left-0' : '' }}"></div>
                    <a href="/admin/restaurant-accounts" class="pt-3 pb-3 text-center">
                        <i class="fas fa-user-tie"></i>
                    </a>
                </div>
                <div class="grid items-center justify-center mt-4 text-white hover:bg-sideBarHoverBgColor">
                    <a href="/admin/customer-accounts" class="pt-3 pb-3"><i class="fas fa-users"></i></a>
                </div>
            </div>
            <div class="bg-black min-h-screen w-80">
                <div class="grid items-center mt-8 text-white hover:bg-sideBarHoverBgColor">
                    @if (request()->is('admin/restaurant-applicants/*'))
                        <a href="/admin/restaurant-applicants" class="pt-3 pb-3"><i class="fas fa-arrow-left mr-4 ml-8"></i> Restaurant Applicants</a>
                    @elseif (request()->is('admin/restaurant-accounts/*'))
                        <a href="/admin/restaurant-accounts" class="pt-3 pb-3"><i class="fas fa-arrow-left mr-4 ml-8"></i> Restaurant Accounts</a>
                    @else
                        <a href="/admin/customer-accounts" class="pt-3 pb-3"><i class="fas fa-arrow-left mr-4 ml-8"></i> Customer Accounts</a>
                    @endif
                </div>
            </div>
            <div class="bg-gray-200 w-full">
                @yield('content')
            </div>
        </div>
    </section>
    <script type="text/javascript" src="{{ asset('js/test.js') }}"></script>
</body>
</html>