@extends('restaurant.app2')

@section('content')
<div class="w-11/12 mx-auto restaurant-about mt-10 mb-10">
    @if (session()->has('logoUpdated'))
        <script>
            Swal.fire(
                'Logo Updated',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('gcashQrUpdated'))
        <script>
            Swal.fire(
                'Gcash Qr Code Updated',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('passwordUpdated'))
        <script>
            Swal.fire(
                'Password Updated',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('currentPassNotMatch'))
        <script>
            Swal.fire(
                'Current Password Does not match',
                '',
                'error'
            );
        </script>
    @endif
    @if (session()->has('usernameUpdated'))
        <script>
            Swal.fire(
                'Username Updated',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('usernameEqual'))
        <script>
            Swal.fire(
                'Username did not change',
                '',
                'warning'
            );
        </script>
    @endif
    
    
    <div class="relative w-full h-56">
        @if ($account->rLogo == "" || $account->rLogo == null)
        <div style="background-image: linear-gradient(rgba(255, 226, 226, 0.75), rgba(255, 226, 226, 0.75)), url({{asset('images/defaultAccountImage.png')}}); background-repeat: no-repeat; background-size: cover; background-position: center;" class="h-full"></div>
        @else
        <div style="background-image: linear-gradient(rgba(255, 226, 226, 0.75), rgba(255, 226, 226, 0.75)), url({{asset('uploads/restaurantAccounts/logo/'.$id.'/'.$account->rLogo)}}); background-repeat: no-repeat; background-size: cover; background-position: center;" class="h-full"></div>
        @endif
        <div class="absolute top-0 left-headerLogoLeftMargin h-full">
            <div class="grid grid-cols-1 items-center h-full">
                <div class="col-span-1 grid grid-cols-profileHeaderGrid items-center">
                    @if ($account->rLogo == "" || $account->rLogo == null)
                        <img src="{{ asset('images/defaultAccountImage.png') }}" alt="accountImage" class="w-44 h-44">
                    @else
                        <img src="{{ asset('uploads/restaurantAccounts/logo/'.$id.'/'.$account->rLogo) }}" alt="accountImage" class="w-44 h-44">
                    @endif
                    <div>
                        <p class="uppercase font-bold text-4xl">{{ $account->rName }}</p>
                        <p class="text-lg">{{ $account->rBranch }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="min-h-screen">
        <div class="bg-white">
            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Restaurant Information</div>
            <div class="grid grid-cols-formsThreeCols w-10/12 mx-auto mt-5 mb-5">
                <div class="text-left text-submitButton">Name</div>
                <div class="text-left text-submitButton">:</div>
                <div class="font-bold mb-4">{{ $account->rName }}</div>
                
                <div class="text-left text-submitButton">Branch</div>
                <div class="text-left text-submitButton">:</div>
                <div class="font-bold mb-4">{{ $account->rBranch }}</div>
                
                <div class="text-left text-submitButton">Location</div>
                <div class="text-left text-submitButton">:</div>
                <div class="font-bold mb-4">{{ $account->rAddress.', '.$account->rCity.', '.$account->rState.', '.$account->rCountry }}</div>
                
                <div class="text-left text-submitButton">Zip Code</div>
                <div class="text-left text-submitButton">:</div>
                <div class="font-bold mb-4">{{ $account->rPostalCode }}</div>
                
                <div class="text-left text-submitButton">Verified</div>
                <div class="text-left text-submitButton">:</div>
                <div class="font-bold mb-4">{{ $account->verified }}</div>
                
                <div class="text-left text-submitButton">Status</div>
                <div class="text-left text-submitButton">:</div>
                <div class="font-bold mb-4">{{ $account->status }}</div>
            </div>


            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Contact Information</div>
            <form action="/restaurant/manage-restaurant/about/restaurant-information/updateContact" method="POST">
                @csrf
                <div class="grid grid-cols-formsThreeCols w-10/12 mx-auto mt-5 mb-3">
                    <div class="text-left text-submitButton">Phone</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="text" name="contactNumber" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  {{ $errors->has('contactNumber') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}" value="{{ $account->contactNumber }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('contactNumber'){{ $message }}@enderror</span>
                    </div>
                    
                    <div class="text-left text-submitButton">Landline</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="text" name="landlineNumber" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  {{ $errors->has('landlineNumber') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}" value="{{ $account->landlineNumber }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('landlineNumber'){{ $message }}@enderror</span>
                    </div>
                    
                    <div class="text-left text-submitButton">Email</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="text" name="emailAddress" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  {{ $errors->has('emailAddress') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}" value="{{ $account->emailAddress }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('emailAddress'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="w-10/12 mx-auto text-right mb-5">
                    <button type="submit" class="bg-submitButton text-white w-36 h-9 rounded-md">Update Contact</button>
                </div>
            </form>

            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Owner/Staff Information</div>
            <form action="/restaurant/manage-restaurant/about/restaurant-information/updateUsername" method="POST">
                @csrf
                <div class="grid grid-cols-formsThreeCols w-10/12 mx-auto mt-5 mb-3">
                    <div class="text-left text-submitButton">Name</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">{{ $account->fname.' '.$account->mname.' '.$account->lname }}</div>
                    
                    <div class="text-left text-submitButton">Location</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">{{ $account->address.', '.$account->city.', '.$account->state.', '.$account->country }}</div>
                    
                    <div class="text-left text-submitButton">Zip Code</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">{{ $account->postalCode }}</div>
                    
                    <div class="text-left text-submitButton">Role</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">{{ $account->role }}</div>
                    
                    <div class="text-left text-submitButton">Birthdate</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">{{ $account->birthDate }}</div>
                    
                    <div class="text-left text-submitButton">Gender</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">{{ $account->gender }}</div>
                    
                    <div class="text-left text-submitButton">Username</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="text" name="username" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  {{ $errors->has('username') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}" value="{{ $account->username }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('username'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="w-10/12 mx-auto text-right mb-5">
                    <button type="submit" class="bg-submitButton text-white w-36 h-9 rounded-md">Update Username</button>
                </div>
            </form>


            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Reset Password</div>
            <form action="/restaurant/manage-restaurant/about/restaurant-information/updatePassword" method="POST">
                @csrf
                <div class="grid grid-cols-formsThreeCols w-10/12 mx-auto mt-5">
                    <div class="text-left text-submitButton">Old Password</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="password" name="oldPassword" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('oldPassword') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('oldPassword'){{ $message }}@enderror</span>
                    </div>

                    
                    <div class="text-left text-submitButton">New Password</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="password" name="newPassword" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('newPassword') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('newPassword'){{ $message }}@enderror</span>
                    </div>

                    
                    <div class="text-left text-submitButton">Confirm Password</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="password" name="confirmPassword" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('confirmPassword') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('confirmPassword'){{ $message }}@enderror</span>
                    </div>

                    
                </div>
                <div class="w-10/12 mx-auto text-right mt-3 mb-5">
                    <button type="submit" class="bg-submitButton text-white w-36 h-9 rounded-md">Reset Password</button>
                </div>
            </form>


            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Update Restaurant Logo</div>
            <form action="/restaurant/manage-restaurant/about/restaurant-information/updateLogo" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-formsThreeCols w-10/12 mx-auto mt-5">
                    <div class="text-left text-submitButton">Logo</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="file" name="restaurantLogo" onchange="previewLogo(this);" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('restaurantLogo') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('restaurantLogo'){{ "Please choose image first" }}@enderror</span>
                    </div>
                </div>
                <div class="flex w-10/12 mx-auto pb-5">
                    @if ($account->rLogo == "" || $account->rLogo == null)
                        <img src="{{ asset('images/defaultAccountImage.png') }}" id="restaurantLogo" alt="restaurantLogo" class="border-multiStepBoxBorder border-gray-300 w-36 h-36">
                    @else
                        <img src="{{ asset('uploads/restaurantAccounts/logo/'.$id.'/'.$account->rLogo) }}" id="restaurantLogo" alt="restaurantLogo" class="border-multiStepBoxBorder border-gray-300 w-36 h-36">
                    @endif
                    <div class="w-full mx-auto text-right">
                        <button type="submit" class="bg-submitButton text-white w-36 h-9 rounded-md">Update Logo</button>
                    </div>
                </div>
            </form>

            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Update GCash QrCode Image</div>
            <form action="/restaurant/manage-restaurant/about/restaurant-information/updateGcashQr" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-formsThreeCols w-10/12 mx-auto mt-5">
                    <div class="text-left text-submitButton">Gcash Qr Code</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="file" name="restaurantGcashQr" onchange="previewGcashQr(this);" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('restaurantGcashQr') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('restaurantGcashQr'){{ "Please choose image first" }}@enderror</span>
                    </div>
                </div>
                <div class="flex w-10/12 mx-auto pb-5">
                    @if ($account->rGcashQrCodeImage == "" || $account->rGcashQrCodeImage == null)
                        <img src="{{ asset('images/defaultAccountImage.png') }}" id="restaurantGcashQr" alt="restaurantGcashQr" class="border-multiStepBoxBorder border-gray-300 w-36 h-36">
                    @else
                        <img src="{{ asset('uploads/restaurantAccounts/gcashQr/'.$id.'/'.$account->rGcashQrCodeImage) }}" id="restaurantGcashQr" alt="restaurantGcashQr" class="border-multiStepBoxBorder border-gray-300 w-36 h-36">
                    @endif
                    <div class="w-full mx-auto text-right">
                        <button type="submit" class="bg-submitButton text-white w-36 h-9 rounded-md">Update Qr Code</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection