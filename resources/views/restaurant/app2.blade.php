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
                    @if (request()->is('restaurant/manage-restaurant/*'))
                        <a href="/restaurant/dashboard" class="pt-3 pb-3 hover:bg-sideBarHoverBgColor hover:text-gray-300 transition duration-200 ease-in-out">
                            <i class="fas fa-arrow-left mr-4 ml-8 "></i> Manage Restaurant
                        </a>
                        <div class="text-sm mt-5 w-10/12 mx-auto">
                            <a href="/restaurant/manage-restaurant/about/restaurant-information" class="ml-5 {{ (request()->is('restaurant/manage-restaurant/about/*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> About Restaurant</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                        <div class="text-sm mt-3 w-10/12 mx-auto">
                            <a href="/restaurant/manage-restaurant/food-menu/food-item" class="ml-5 {{ (request()->is('restaurant/manage-restaurant/food-menu/*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> Food Menu</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                        <div class="text-sm mt-3 w-10/12 mx-auto">
                            <a href="/restaurant/manage-restaurant/task-rewards/stamp-card" class="ml-5 {{ (request()->is('restaurant/manage-restaurant/task-rewards*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> Tasks & Rewards</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                        <div class="text-sm mt-3 w-10/12 mx-auto">
                            <a href="/restaurant/manage-restaurant/promo" class="ml-5 {{ (request()->is('restaurant/manage-restaurant/promo*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> Promos</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                        <div class="text-sm mt-3 w-10/12 mx-auto">
                            <a href="/restaurant/manage-restaurant/time/store-hours" class="ml-5 {{ (request()->is('restaurant/manage-restaurant/time*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> Manage Time</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                        <div class="text-sm mt-3 w-10/12 mx-auto">
                            <a href="/restaurant/manage-restaurant/offense/offenses" class="ml-5 {{ (request()->is('restaurant/manage-restaurant/offense*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> Manage Offense</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                        <div class="text-sm mt-3 w-10/12 mx-auto">
                            <a href="/restaurant/manage-restaurant/checklist" class="ml-5 {{ (request()->is('restaurant/manage-restaurant/checklist*')) ? 'text-manageRestaurantSidebarColorActive' : 'text-manageRestaurantSidebarColor hover:underline' }}"><i class="fas fa-file w-3 mr-3"></i> Current Status</a>
                            <div class="border-multiStepBoxBorder border-manageRestaurantHrColor mt-3"></div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="bg-gray-200 w-full">
                @if (request()->is('restaurant/manage-restaurant/food-menu/*'))
                    <div class="grid grid-cols-3 gap-x-1 w-full px-1 mt-1 text-center font-bold uppercase">
                        <a href="/restaurant/manage-restaurant/food-menu/food-item" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/food-menu/food-item*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Food Item</a>
                        <a href="/restaurant/manage-restaurant/food-menu/food-set" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/food-menu/food-set*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Food Set</a>
                        <a href="/restaurant/manage-restaurant/food-menu/order-set" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/food-menu/order-set*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Order Set</a>
                    </div>
                @endif
                @if (request()->is('restaurant/manage-restaurant/about/*'))
                    <div class="grid grid-cols-2 gap-x-1 w-full px-1 mt-1 text-center font-bold uppercase">
                        <a href="/restaurant/manage-restaurant/about/restaurant-information" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/about/restaurant-information*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Restaurant Information</a>
                        <a href="/restaurant/manage-restaurant/about/restaurant-post" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/about/restaurant-post*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Post</a>
                    </div>
                @endif
                @if (request()->is('restaurant/manage-restaurant/time/*'))
                    <div class="grid grid-cols-3 gap-x-1 w-full px-1 mt-1 text-center font-bold uppercase">
                        <a href="/restaurant/manage-restaurant/time/store-hours" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/time/store-hours*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Store Hours</a>
                        <a href="/restaurant/manage-restaurant/time/time-limit" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/time/time-limit*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Dine in time limit</a>
                        <a href="/restaurant/manage-restaurant/time/unavailable-dates" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/time/unavailable-dates*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Unavailable Dates</a>
                    </div>
                @endif
                @if (request()->is('restaurant/manage-restaurant/task-rewards/*'))
                    <div class="grid grid-cols-3 gap-x-1 w-full px-1 mt-1 text-center font-bold uppercase">
                        <a href="/restaurant/manage-restaurant/task-rewards/stamp-card" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/task-rewards/stamp-card*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Stamp Card</a>
                        <a href="/restaurant/manage-restaurant/task-rewards/rewards" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/task-rewards/rewards*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Rewards</a>
                        <a href="/restaurant/manage-restaurant/task-rewards/tasks" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/task-rewards/tasks*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Tasks</a>
                    </div>
                @endif
                @if (request()->is('restaurant/manage-restaurant/offense/*'))
                    <div class="grid grid-cols-2 gap-x-1 w-full px-1 mt-1 text-center font-bold uppercase">
                        <a href="/restaurant/manage-restaurant/offense/offenses" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/offense/offenses*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Offenses</a>
                        <a href="/restaurant/manage-restaurant/offense/policy" class="w-full py-3 rounded-sm {{ (request()->is('restaurant/manage-restaurant/offense/policy*')) ? 'bg-tableBgHeader text-submitButton shadow-adminDownloadButton ' : 'bg-white text-manageRestaurantSidebarColor' }}">Restaurant Policy</a>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </section>
    @if (request()->is('restaurant/manage-restaurant/food-menu/food-set/detail/*'))
        <script type="text/javascript" src="{{ asset('js/foodSetDetail.js') }}"></script>
    @elseif (request()->is('restaurant/manage-restaurant/food-menu/order-set/*'))
        <script type="text/javascript" src="{{ asset('js/orderSet.js') }}"></script>
    @elseif (request()->is('restaurant/manage-restaurant/about/restaurant-information*'))
        <script type="text/javascript" src="{{ asset('js/restaurantInformation.js') }}"></script>
    @elseif (request()->is('restaurant/manage-restaurant/about/restaurant-post*'))
        <script type="text/javascript" src="{{ asset('js/restaurantPost.js') }}"></script>
    @elseif (request()->is('restaurant/manage-restaurant/promo*'))
        <script type="text/javascript" src="{{ asset('js/promo.js') }}"></script>
    @elseif (request()->is('restaurant/manage-restaurant/time/store-hours*'))
        <script type="text/javascript" src="{{ asset('js/storeHours.js') }}"></script>
    @elseif (request()->is('restaurant/manage-restaurant/time/unavailable-dates*'))
        <script type="text/javascript" src="{{ asset('js/unavailableDates.js') }}"></script>
    @elseif (request()->is('restaurant/manage-restaurant/offense/policy*'))
        <script type="text/javascript" src="{{ asset('js/policy.js') }}"></script>
    @elseif (request()->is('restaurant/manage-restaurant/offense/*'))
        <script type="text/javascript" src="{{ asset('js/offense.js') }}"></script>
    @elseif (request()->is('restaurant/manage-restaurant/checklist*'))
        <script type="text/javascript" src="{{ asset('js/checklist.js') }}"></script>
    @elseif (request()->is('restaurant/manage-restaurant/task-rewards/rewards*'))
        <script type="text/javascript" src="{{ asset('js/rewards.js') }}"></script>
    @elseif (request()->is('restaurant/manage-restaurant/task-rewards/tasks*'))
        <script type="text/javascript" src="{{ asset('js/tasks.js') }}"></script>
    @else
        <script type="text/javascript" src="{{ asset('js/foodItem.js') }}"></script>
    @endif
</body>
</html>