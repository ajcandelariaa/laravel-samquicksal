@extends('admin.app2')

@section('content')
<div class="w-full restaurant-account">
    <div class="relative w-full h-56">
        @if ($account->profileImage == null)
            <div style="background-image: linear-gradient(rgba(255, 226, 226, 0.75), rgba(255, 226, 226, 0.75)), url({{asset('images/user-default.png')}}); background-repeat: no-repeat; background-size: cover; background-position: center;" class="h-full"></div>
        @else
            <div style="background-image: linear-gradient(rgba(255, 226, 226, 0.75), rgba(255, 226, 226, 0.75)), url({{asset('uploads/customerAccounts/logo/'.$account->id.'/'.$account->profileImage)}}); background-repeat: no-repeat; background-size: cover; background-position: center;" class="h-full"></div>
        @endif
        
        <div class="absolute top-0 left-headerLogoLeftMargin h-full">
            <div class="grid grid-cols-1 items-center h-full">
                <div class="col-span-1 grid grid-cols-2 items-center">
                    @if ($account->profileImage == null)
                        <img src="{{ asset('images/user-default.png') }}" alt="accountImage" class="w-44 h-44">
                    @else
                        <img src="{{ asset('uploads/customerAccounts/logo/'.$account->id.'/'.$account->profileImage) }}" alt="accountImage" class="w-44 h-44">
                    @endif
                    <div>
                        <p class="uppercase font-bold text-4xl">{{ $account->name }}</p>
                        <p class="text-lg">{{ $account->emailAddress }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full grid grid-cols-12 min-h-screen">
        <div class="col-span-7 border-r-multiStepBoxBorder border-gray-400 bg-white">
            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Basic Information</div>
            <div class="grid grid-cols-6 w-10/12 mx-auto mt-5 mb-5">
                <div class="col-span-1 text-left text-submitButton">Name</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->name }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Email Address</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->emailAddress }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Contact Number</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->contactNumber }}</div>
                
                <div class="col-span-1 text-left text-submitButton">User Since</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $userJoined }}</div>
            </div>

        </div>
        
        <div class="col-span-5 bg-white">
        </div>
            
    </div>
</div>
@endsection