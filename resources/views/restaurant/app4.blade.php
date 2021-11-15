<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Samquicksal Restaurant</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
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
                    <a href="/restaurant/logout" class="text-white hover:underline hover:text-gray-300 transition duration-200 ease-in-out">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="flex flex-column">
            {{-- w-96 384px --}}
            <div class="bg-sideBarBgColor min-h-screen w-16">
                <div class="grid items-center mt-8 text-white {{ (request()->is('restaurant/dashboard*')) ? 'relative bg-black' : 'hover:bg-sideBarHoverBgColor' }}">
                    <div class="{{ (request()->is('restaurant/dashboard*')) ? 'h-full w-1 bg-headerActiveTextColor absolute left-0' : '' }}"></div>
                    <a href="/restaurant/dashboard" class="pt-3 pb-3 text-center">
                        <i class="fas fa-th-large"></i>
                    </a>
                </div>
                <div class="grid items-center mt-4 text-white {{ (request()->is('restaurant/live-transaction*')) ? 'relative bg-black' : 'hover:bg-sideBarHoverBgColor' }}">
                    <div class="{{ (request()->is('restaurant/live-transaction*')) ? 'h-full w-1 bg-headerActiveTextColor absolute left-0' : '' }}"></div>
                    <a href="/restaurant/live-transaction/customer-booking/queue" class="pt-3 pb-3 text-center">
                        <i class="fas fa-podcast"></i>
                    </a>
                </div>
                <div class="grid items-center mt-4 text-white {{ (request()->is('restaurant/transaction-history*')) ? 'relative bg-black' : 'hover:bg-sideBarHoverBgColor' }}">
                    <div class="{{ (request()->is('restaurant/transaction-history*')) ? 'h-full w-1 bg-headerActiveTextColor absolute left-0' : '' }}"></div>
                    <a href="/restaurant/transaction-history/cancelled/queue" class="pt-3 pb-3 text-center">
                        <i class="fas fa-history"></i>
                    </a>
                </div>
                <div class="grid items-center mt-4 text-white {{ (request()->is('restaurant/stamp-offenses*')) ? 'relative bg-black' : 'hover:bg-sideBarHoverBgColor' }}">
                    <div class="{{ (request()->is('restaurant/stamp-offenses*')) ? 'h-full w-1 bg-headerActiveTextColor absolute left-0' : '' }}"></div>
                    <a href="/restaurant/stamp-offenses/stamp-history" class="pt-3 pb-3 text-center">
                        <i class="fas fa-stamp"></i>
                    </a>
                </div>
                <div class="grid items-center mt-4 text-white {{ (request()->is('restaurant/manage-restaurant*')) ? 'relative bg-black' : 'hover:bg-sideBarHoverBgColor' }}">
                    <div class="{{ (request()->is('restaurant/manage-restaurant*')) ? 'h-full w-1 bg-headerActiveTextColor absolute left-0' : '' }}"></div>
                    <a href="/restaurant/manage-restaurant/about/restaurant-information" class="pt-3 pb-3 text-center">
                        <i class="fas fa-file-signature"></i>
                    </a>
                </div>
            </div>
            <div class="bg-black min-h-screen w-80">
                <div class="grid items-center mt-8 text-white">
                    @if (request()->is('restaurant/transaction-history/*'))
                        <a href="/restaurant/dashboard" class="pt-3 pb-3 hover:bg-sideBarHoverBgColor hover:text-gray-300 transition duration-200 ease-in-out">
                            <i class="fas fa-arrow-left mr-4 ml-8 "></i> Transaction History
                        </a>
                        <div class="text-sm mt-5 w-10/12 mx-auto">
                            <a href="/restaurant/transaction-history/cancelled/queue" class="ml-5 {{ (request()->is('restaurant/transaction-history/cancelled*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> Customer Cancelled</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                        <div class="text-sm mt-5 w-10/12 mx-auto">
                            <a href="/restaurant/transaction-history/declined/queue" class="ml-5 {{ (request()->is('restaurant/transaction-history/declined*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> Customer Declined</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                        <div class="text-sm mt-5 w-10/12 mx-auto">
                            <a href="/restaurant/transaction-history/no-show/queue" class="ml-5 {{ (request()->is('restaurant/transaction-history/no-show*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> Customer No Show</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                        <div class="text-sm mt-5 w-10/12 mx-auto">
                            <a href="/restaurant/transaction-history/runaway/queue" class="ml-5 {{ (request()->is('restaurant/transaction-history/runaway*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> Customer Runaway</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                        <div class="text-sm mt-5 w-10/12 mx-auto">
                            <a href="/restaurant/transaction-history/completed/queue" class="ml-5 {{ (request()->is('restaurant/transaction-history/completed*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> Customer Completed</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="bg-gray-200 w-full">
                @if (request()->is('restaurant/transaction-history/cancelled*'))
                    <div class="grid grid-cols-2 gap-x-1 w-full px-1 mt-1 text-center font-bold uppercase">
                        <a href="/restaurant/transaction-history/cancelled/queue" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/transaction-history/cancelled/queue*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Customer Queue</a>
                        <a href="/restaurant/transaction-history/cancelled/reserve" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/transaction-history/cancelled/reserve*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Customer Reserve</a>
                    </div>
                @elseif (request()->is('restaurant/transaction-history/declined*'))
                    <div class="grid grid-cols-2 gap-x-1 w-full px-1 mt-1 text-center font-bold uppercase">
                        <a href="/restaurant/transaction-history/declined/queue" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/transaction-history/declined/queue*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Customer Queue</a>
                        <a href="/restaurant/transaction-history/declined/reserve" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/transaction-history/declined/reserve*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Customer Reserve</a>
                    </div>
                @elseif (request()->is('restaurant/transaction-history/no-show*'))
                    <div class="grid grid-cols-2 gap-x-1 w-full px-1 mt-1 text-center font-bold uppercase">
                        <a href="/restaurant/transaction-history/no-show/queue" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/transaction-history/no-show/queue*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Customer Queue</a>
                        <a href="/restaurant/transaction-history/no-show/reserve" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/transaction-history/no-show/reserve*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Customer Reserve</a>
                    </div>
                @elseif (request()->is('restaurant/transaction-history/runaway*'))
                    <div class="grid grid-cols-2 gap-x-1 w-full px-1 mt-1 text-center font-bold uppercase">
                        <a href="/restaurant/transaction-history/runaway/queue" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/transaction-history/runaway/queue*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Customer Queue</a>
                        <a href="/restaurant/transaction-history/runaway/reserve" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/transaction-history/runaway/reserve*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Customer Reserve</a>
                    </div>
                @elseif (request()->is('restaurant/transaction-history/completed*'))
                    <div class="grid grid-cols-2 gap-x-1 w-full px-1 mt-1 text-center font-bold uppercase">
                        <a href="/restaurant/transaction-history/completed/queue" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/transaction-history/completed/queue*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Customer Queue</a>
                        <a href="/restaurant/transaction-history/completed/reserve" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/transaction-history/completed/reserve*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Customer Reserve</a>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </section>
</body>
</html>