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
    @elseif (session()->has('gcashQrUpdated'))
        <script>
            Swal.fire(
                'Gcash Qr Code Updated',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('passwordUpdated'))
        <script>
            Swal.fire(
                'Password Updated',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('tablesUpdated'))
        <script>
            Swal.fire(
                'Tables Updated',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('storeStillopen'))
        <script>
            Swal.fire(
                'Cant update tables',
                'Your store is still open wait for the closing hours to update the tables',
                'error'
            );
        </script>
    @elseif (session()->has('usernameUpdated'))
        <script>
            Swal.fire(
                'Username Updated',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('contactUpdated'))
         <script>
             Swal.fire(
                 'Contact Updated',
                 '',
                 'success'
             );
         </script>
    @elseif (session()->has('emailAddressUpdated'))
         <script>
             Swal.fire(
                 'Email Address Updated',
                 'We\'ve emailed you the link to verify your new email',
                 'success'
             );
         </script>
    @elseif (session()->has('radiusUpdated'))
         <script>
             Swal.fire(
                 'Radius Updated',
                 '',
                 'success'
             );
         </script>
    @elseif (session()->has('emailAddressNotVerified'))
        <script>
            Swal.fire(
                'Current Email Address is not yet verified',
                'Please-in-oute verify it first before changing your email',
                'error'
            );
        </script>    
     @elseif (session()->has('resendEmailVerificationSent'))
        <script>
            Swal.fire(
                'Verification Link has been sent to your email',
                '',
                'success'
            );
        </script>      
     @elseif (session()->has('locationUpdated'))
        <script>
            Swal.fire(
                'Location Submitted',
                '',
                'success'
            );
        </script>   
    @endif
    
    <div class="relative w-full h-56">
        @if ($account->rLogo == "" || $account->rLogo == null)
        <div style="background-image: linear-gradient(rgba(255, 226, 226, 0.75), rgba(255, 226, 226, 0.75)), url({{asset('images/resto-default.png')}}); background-repeat: no-repeat; background-size: cover; background-position: center;" class="h-full"></div>
        @else
        <div style="background-image: linear-gradient(rgba(255, 226, 226, 0.75), rgba(255, 226, 226, 0.75)), url({{asset('uploads/restaurantAccounts/logo/'.$id.'/'.$account->rLogo)}}); background-repeat: no-repeat; background-size: cover; background-position: center;" class="h-full"></div>
        @endif
        <div class="absolute top-0 left-headerLogoLeftMargin h-full">
            <div class="grid grid-cols-1 items-center h-full">
                <div class="col-span-1 grid grid-cols-profileHeaderGrid items-center">
                    @if ($account->rLogo == "" || $account->rLogo == null)
                        <img src="{{ asset('images/resto-default.png') }}" alt="accountImage" class="w-44 h-44">
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
                <div class="font-bold mb-4">
                    {{ $account->verified }}
                    @if ($account->verified == "No")
                        <a href="/restaurant/manage-restaurant/about/restaurant-information/resend-email-verification/{{ $account->emailAddress }}/{{ $account->fname }}/{{ $account->lname }}" class="ml-2 text-xs text-manageRestaurantSidebarColorActive hover:underline">Re-send verification link</a>
                    @endif
                </div>
                
                <div class="text-left text-submitButton">Status</div>
                <div class="text-left text-submitButton">:</div>
                <div class="font-bold mb-4">{{ $account->status }}</div>

                    
                @if ($account->rLatitudeLoc == null && $account->rLongitudeLoc == null)
                    <div class="text-left text-submitButton">Locate Restaurant</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">Click <a href="/restaurant/manage-restaurant/about/restaurant-information/updateLocation" class="underline text-purple-500">here</a> to locate your restaurant</div>
                @endif
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
                </div>
                <div class="w-10/12 mx-auto text-right mb-5">
                    <button type="submit" class="bg-submitButton hover:bg-btnHoverColor text-white hover:text-gray-300 w-36 h-9 rounded-md transition duration-200 ease-in-out ">Update Contact</button>
                </div>
            </form>

            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Update Email Address</div>
            <form action="/restaurant/manage-restaurant/about/restaurant-information/updateEmailAddress" method="POST">
                @csrf
                <div class="grid grid-cols-formsThreeCols w-10/12 mx-auto mt-5 mb-3">
                    <div class="text-left text-submitButton">Email</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        @if (session()->has('emailAddressEqual'))
                            <input type="text" name="emailAddress" class="w-full border rounded-sm text-sm text-gray-700 focus:outline-none  border-red-600 focus:border-red-600" value="{{ $account->emailAddress }}">
                        @else
                            <input type="text" name="emailAddress" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  {{ $errors->has('emailAddress') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}" value="{{ $account->emailAddress }}">
                        @endif
                        <span class="mt-2 text-red-600 italic text-sm">
                            @error('emailAddress'){{ $message }}@enderror
                            @if (session()->has('emailAddressEqual'))
                                Email Address is the same as your old email address
                            @endif
                        </span>
                    </div>
                </div>
                <div class="w-10/12 mx-auto text-right mb-5">
                    <button type="submit" class="bg-submitButton hover:bg-btnHoverColor text-white hover:text-gray-300 w-36 h-9 rounded-md transition duration-200 ease-in-out ">Update Email</button>
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
                        @if (session()->has('usernameEqual'))
                            <input type="text" name="username" class="w-full border rounded-sm text-sm text-gray-700 focus:outline-none border-red-600 focus:border-red-600" value="{{ $account->username }}">
                        @else
                            <input type="text" name="username" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none  {{ $errors->has('username') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}" value="{{ $account->username }}">
                        @endif
                        <span class="mt-2 text-red-600 italic text-sm">
                            @error('username'){{ $message }}@enderror
                            @if (session()->has('usernameEqual'))
                                Username is the same as your old username
                            @endif
                        </span>
                    </div>
                </div>
                <div class="w-10/12 mx-auto text-right mb-5">
                    <button type="submit" class="bg-submitButton hover:bg-btnHoverColor text-white hover:text-gray-300 w-36 h-9 rounded-md transition duration-200 ease-in-out ">Update Username</button>
                </div>
            </form>


            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Tables</div>
            
            <div class="w-10/12 mx-auto mt-5">
                <p>Total Tables: {{ $account->rNumberOfTables }}</p>
            </div>
            <form action="/restaurant/manage-restaurant/about/restaurant-information/updateTables" id="formUpdateTables" method="POST">
                @csrf
                <div class="grid grid-cols-formsThreeCols w-10/12 mx-auto mt-2">
                    <div class="text-left text-submitButton">2 seater</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="number" min="0" max="50" name="r2seater" value="{{ $account->r2seater }}" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('r2seater') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('r2seater'){{ $message }}@enderror</span>
                    </div>
                    
                    <div class="text-left text-submitButton">3 seater</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="number" min="0" max="50"  name="r3seater" value="{{ $account->r3seater }}" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('r3seater') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('r3seater'){{ $message }}@enderror</span>
                    </div>

                    
                    <div class="text-left text-submitButton">4 seater</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="number" min="0" max="50"  name="r4seater" value="{{ $account->r4seater }}" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('r4seater') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('r4seater'){{ $message }}@enderror</span>
                    </div>

                    
                    <div class="text-left text-submitButton">5 seater</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="number" min="0" max="50"  name="r5seater" value="{{ $account->r5seater }}" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('r5seater') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('r5seater'){{ $message }}@enderror</span>
                    </div>

                    <div class="text-left text-submitButton">6 seater</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="number" min="0" max="50"  name="r6seater" value="{{ $account->r6seater }}" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('r6seater') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('r6seater'){{ $message }}@enderror</span>
                    </div>

                    <div class="text-left text-submitButton">7 seater</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="number" min="0" max="50"  name="r7seater" value="{{ $account->r7seater }}" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('r7seater') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('r7seater'){{ $message }}@enderror</span>
                    </div>

                    <div class="text-left text-submitButton">8 seater</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="number" min="0" max="50"  name="r8seater" value="{{ $account->r8seater }}" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('r8seater') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('r8seater'){{ $message }}@enderror</span>
                    </div>

                    <div class="text-left text-submitButton">9 seater</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="number" min="0" max="50"  name="r9seater" value="{{ $account->r9seater }}" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('r9seater') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('r9seater'){{ $message }}@enderror</span>
                    </div>

                    <div class="text-left text-submitButton">10 seater</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="number" min="0" max="50"  name="r10seater" value="{{ $account->r10seater }}" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('r10seater') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('r10seater'){{ $message }}@enderror</span>
                    </div>

                </div>
                <div class="w-10/12 mx-auto text-right mt-3 mb-5">
                    <button type="submit" id="btnUpdateTables" class="bg-submitButton hover:bg-btnHoverColor text-white hover:text-gray-300 w-36 h-9 rounded-md transition duration-200 ease-in-out ">Update Tables</button>
                </div>
            </form>


            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Reset Password</div>
            <form action="/restaurant/manage-restaurant/about/restaurant-information/updatePassword" method="POST">
                @csrf
                <div class="grid grid-cols-formsThreeCols w-10/12 mx-auto mt-5">
                    <div class="text-left text-submitButton">Current Password</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        @if (session()->has('currentPassNotMatch'))
                            <input type="password" name="oldPassword" class="w-full border rounded-sm text-sm text-gray-700 focus:outline-none border-red-600 focus:border-red-600">
                        @else
                            <input type="password" name="oldPassword" class="w-full border border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('oldPassword') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}" value="{{ old('oldPassword') }}">
                        @endif
                        <span class="mt-2 text-red-600 italic text-sm">
                            @error('oldPassword'){{ $message }}@enderror
                            @if (session()->has('currentPassNotMatch'))
                                Current Password does not match
                            @endif
                        </span>
                    </div>

                    
                    <div class="text-left text-submitButton">New Password</div>
                    <div class="text-left text-submitButton">:</div>
                    <div class="font-bold mb-4">
                        <input type="hidden" name="currentPassword" value="{{ $account->password }}">
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
                    <button type="submit" class="bg-submitButton hover:bg-btnHoverColor text-white hover:text-gray-300 w-36 h-9 rounded-md transition duration-200 ease-in-out ">Reset Password</button>
                </div>
            </form>


            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Update Restaurant Radius</div>
            <form action="/restaurant/manage-restaurant/about/restaurant-information/updateRadius" method="POST">
                @csrf
                <div class="grid grid-cols-formsThreeCols w-10/12 mx-auto mt-5">
                    <div class="text-left text-submitButton">Meters</div>
                    <div class="text-left text-submitButton">:</div>
                    <div>
                        <div class="font-bold mb-4">
                            <button id="btn-radiusMin" type="button" class="bg-gray-400 hover:bg-gray-500 px-3 rounded-sm text-sm">Min</button>
                            <input type="number" id="inputRadius" name="rRadius" min=100 max=10000 value="{{ $account->rRadius }}" class="w-28 border pl-1 border-gray-400 rounded-sm text-sm text-gray-700 focus:outline-none {{ $errors->has('rRadius') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}" required>
                            <span class="mt-2 text-red-600 italic text-sm">@error('rRadius'){{ $message }}@enderror</span>
                            <button id="btn-radiusMax" type="button" class="bg-gray-400 hover:bg-gray-500 px-3 rounded-sm text-sm">Max</button>
                        </div>
                    </div>
                </div>

                <div class="w-10/12 mx-auto text-right mt-3 mb-5">
                    <button type="submit" class="bg-submitButton hover:bg-btnHoverColor text-white hover:text-gray-300 w-36 h-9 rounded-md transition duration-200 ease-in-out ">Update Radius</button>
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
                        <span class="mt-2 text-red-600 italic text-sm">@error('restaurantLogo'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="flex w-10/12 mx-auto pb-5">
                    @if ($account->rLogo == "" || $account->rLogo == null)
                        <img src="{{ asset('images/resto-default.png') }}" id="restaurantLogo" alt="restaurantLogo" class="border-multiStepBoxBorder border-gray-300 w-36 h-36">
                    @else
                        <img src="{{ asset('uploads/restaurantAccounts/logo/'.$id.'/'.$account->rLogo) }}" id="restaurantLogo" alt="restaurantLogo" class="border-multiStepBoxBorder border-gray-300 w-36 h-36">
                    @endif
                    <div class="w-full mx-auto text-right">
                        <button type="submit" class="bg-submitButton hover:bg-btnHoverColor text-white hover:text-gray-300 w-36 h-9 rounded-md transition duration-200 ease-in-out ">Update Logo</button>
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
                        <span class="mt-2 text-red-600 italic text-sm">@error('restaurantGcashQr'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="flex flex-row w-10/12 mx-auto pb-5">
                    @if ($account->rGcashQrCodeImage == "" || $account->rGcashQrCodeImage == null)
                        <img src="{{ asset('images/defaultAccountImage.png') }}" id="restaurantGcashQr" alt="restaurantGcashQr" class="border-multiStepBoxBorder border-gray-300 w-36 h-36">
                    @else
                        <img src="{{ asset('uploads/restaurantAccounts/gcashQr/'.$id.'/'.$account->rGcashQrCodeImage) }}" id="restaurantGcashQr" alt="restaurantGcashQr" class="border-multiStepBoxBorder border-gray-300 w-36 h-36">
                    @endif
                    <div class="w-full mx-auto text-right">
                        <button type="submit" class="bg-submitButton hover:bg-btnHoverColor text-white hover:text-gray-300 w-36 h-9 rounded-md transition duration-200 ease-in-out ">Update Qr Code</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection