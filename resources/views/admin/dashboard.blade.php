@extends('admin.app')

@section('content')
<div class="bg-gray-200 w-full">
    <div class="container mx-auto">
        <div class="w-11/12 mx-auto mt-10 grid grid-cols-12 gap-3">
            {{-- ROW 1 --}}
            <div class="bg-tableBgHeader w-full py-4 grid items-center shadow-lg col-span-full">
                <div class="text-black text-center font-bold text-2xl font-Montserrat">Dashboard</div>
            </div>

            {{-- ROW 2 --}}
            <div class="bg-white w-full h-48 shadow-xl col-span-4 relative">
                <div class="grid text-black text-center items-center w-full h-36 text-8xl font-bold">{{ $restaurantAccountsCount }}</div>
                <div class="bg-black w-full absolute bottom-0 h-12 grid text-white text-center items-center">Restaurant Accounts</div>
            </div>
            <div class="bg-white w-full h-48 shadow-xl col-span-4 relative">
                <div class="grid text-black text-center items-center w-full h-36 text-8xl font-bold">{{ $restaurantApplicantsCount }}</div>
                <div class="bg-black w-full absolute bottom-0 h-12 grid text-white text-center items-center">Restaurant Applicants</div>
            </div>
            <div class="bg-black w-full h-44 shadow-xl col-span-4 rounded-3xl">
                <div class="text-center text-headerActiveTextColor font-medium mt-3 text-sm">Restaurant Applicants</div>
                <div class="text-center text-white font-medium text-xs mt-1">as of Today</div>
                <div class="mt-4 grid grid-cols-adminDashboardThreeCols gap-y-1 w-full justify-center text-white">
                    <div>{{ $approvedCount }}</div>
                    <div><i class="fas fa-caret-up text-green-600"></i></div>
                    <div>Approved</div>

                    <div>{{ $declinedCount }}</div>
                    <div><i class="fas fa-caret-down text-red-600"></i></div>
                    <div>Declined</div>

                    <div>{{ $pendingCount }}</div>
                    <div><i class="fas fa-circle text-circle text-yellow-300"></i></div>
                    <div>Pending</div>
                </div>
            </div>
            
            {{-- ROW 3 --}}
            <div class="bg-white w-full h-48 shadow-xl col-span-8 relative">
                <div class="grid text-black text-center items-center w-full h-36 text-8xl font-bold">{{ $customerAccountsCount }}</div>
                <div class="bg-black w-full absolute bottom-0 h-12 grid text-white text-center items-center">Customer Accounts</div>
            </div>
            <div class="bg-black w-full h-50 shadow-xl col-span-4 rounded-3xl -mt-3">
                <div class="text-center text-headerActiveTextColor font-medium mt-3 text-sm">Notification Panel</div>
                <div class="h-32 w-full bg-multiStepBoxColor mt-3">
                    <div class="pt-3">
                        <div class="w-full h-7 bg-white mb-2">
                            <div class="w-11/12 h-7 mx-auto grid grid-cols-3 items-center">
                                <p class="text-xs col-span-2"><i class="fas fa-file-signature w-6"></i>New Restaurant Applicant</p>
                                <p class="text-gray-600 text-xs col-span-1 justify-self-end">{{ $rAppDiffTime }}</p>
                            </div>
                        </div>
                        <div class="w-full h-7 bg-white mb-2">
                            <div class="w-11/12 h-7 mx-auto grid grid-cols-3 items-center">
                                <p class="text-xs col-span-2"><i class="fas fa-users w-6"></i>New Customer Account</p>
                                <p class="text-gray-600 text-xs col-span-1 justify-self-end">{{ $cAccDiffTime }}</p>
                            </div>
                        </div>
                        <div class="w-full h-7 bg-white">
                            <div class="w-11/12 h-7 mx-auto grid grid-cols-3 items-center">
                                <p class="text-xs col-span-2"><i class="fas fa-user-tie w-6"></i>New Restaurant Account</p>
                                <p class="text-gray-600 text-xs col-span-1 justify-self-end">{{ $rAccDiffTime }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection