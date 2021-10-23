<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Samquicksal - Restaurant Register</title>
    <link rel="icon" href="{{ asset('images/samquicksalLogo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="font-Montserrat" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url({{asset('images/registration-bg.png')}}); background-repeat: no-repeat; background-size: cover;">
    @if (session()->has('registered'))
        <script>
            Swal.fire(
                'Your form has been submitted',
                'Please check your email for further instructions, thank you!',
                'success'
            );
        </script>
    @endif
    <div class="flex items-center justify-center">
        <div class="w-adminDashboardBox max-w-full shadow-2xl bg-white my-10">
            <div class="w-3/5 mx-auto pt-10 pb-12">
            <form action="/restaurant/register2" method="POST" enctype="multipart/form-data">
                @csrf
                <h3 class="text-xl font-semibold">Tell us about yourself</h3>
                <div class="mt-5">
                    <div class="">
                        <label class="text-gray-400 mb-1 ml-1">First Name <span class="text-red-600">*</span></label>
                        <input type="text" name="fname" value="{{ old('fname') }}" class="border {{ $errors->has('fname') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none ">
                            <span class=" text-red-600 italic text-sm">@error('fname'){{ $message }}@enderror</span>
                    </div>

                    <div class="mt-3">
                        <label class="text-gray-400 mb-1 ml-1">Middle Name</label>
                        <input type="text" name="mname" value="{{ old('mname') }}" class="border {{ $errors->has('mname') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                            <span class="mt-2 text-red-600 italic text-sm">@error('mname'){{ $message }}@enderror</span>
                    </div>

                    <div class="mt-3">
                        <label class="text-gray-400 mb-1 ml-1">Last Name <span class="text-red-600">*</span></label>
                        <input type="text" name="lname" value="{{ old('lname') }}" class="border {{ $errors->has('lname') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('lname'){{ $message }}@enderror</span>
                    </div>

                    <div class="mt-3">
                        <label class="text-gray-400 mb-1 ml-1">Role<span class="text-red-600">*</span></label>
                        <select name="role" class="border rounded-lg w-full py-1 px-2 text-sm text-gray-500 focus:outline-none {{ $errors->has('role') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                            <option value="">Please select your role</span></option>
                            <option value="Manager">Manager</option>
                            <option value="Owner">Owner</option>
                            <option value="Staff">Staff</option>
                            <option value="Server">Server</option>
                        </select>
                        <span class="mt-2 text-red-600 italic text-sm">@error('role'){{ $message }}@enderror</span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mt-3">
                        <div class="col-span-1">
                            <label class="text-gray-400 mb-1 ml-1">Birthday<span class="text-red-600">*</span></label>
                            <input type="date" name="birthDate" value="{{ old('birthDate') }}" class="border rounded-lg w-full py-1 px-2 text-sm text-gray-500 focus:outline-none {{ $errors->has('birthDate') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                            <span class="mt-2 text-red-600 italic text-sm">@error('birthDate'){{ $message }}@enderror</span>
                        </div>
                        <div class="col-span-1">
                            <label class="text-gray-400 mb-1 ml-1">Gender<span class="text-red-600">*</span></label>
                            <select name="gender" class="border rounded-lg w-full py-1 px-2 text-sm text-gray-500 focus:outline-none {{ $errors->has('gender') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                                <option value="">Please select your gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Female">Others</option>
                            </select>
                            <span class="mt-2 text-red-600 italic text-sm">@error('gender'){{ $message }}@enderror</span>
                        </div>
                    </div>
                </div>



                <h3 class="text-xl font-semibold">How we may contact you?</h3>
                <div class="grid grid-cols-2 gap-x-5 mt-5">
                    <div class="col-span-full">
                        <label class="text-gray-400 mb-1 ml-1">Address<span class="text-red-600">*</span></label>
                        <input type="text" name="address" value="{{ old('address') }}" class="border {{ $errors->has('address') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('address'){{ $message }}@enderror</span>
                    </div>

                    <div class="mt-3 col-span-full">
                        <label class="text-gray-400 mb-1 ml-1">City<span class="text-red-600">*</span></label>
                        <input type="text" name="city" value="{{ old('city') }}" class="border {{ $errors->has('city') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('city'){{ $message }}@enderror</span>
                    </div>

                    <div class="mt-3 col-span-1">
                        <label class="text-gray-400 mb-1 ml-1">Postal/Zip Code<span class="text-red-600">*</span></label>
                        <input type="text" name="postalCode" value="{{ old('postalCode') }}" class="border {{ $errors->has('postalCode') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('postalCode'){{ $message }}@enderror</span>
                    </div>

                    <div class="mt-3 col-span-1">
                        <label class="text-gray-400 mb-1 ml-1">State/Province<span class="text-red-600">*</span></label>
                        <input type="text" name="state" value="{{ old('state') }}" class="border {{ $errors->has('state') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('state'){{ $message }}@enderror</span>
                    </div>

                    <div class="mt-3 col-span-1">
                        <label class="text-gray-400 mb-1 ml-1">Country<span class="text-red-600">*</span></label>
                        <input type="text" name="country" value="{{ old('country') }}" class="border {{ $errors->has('country') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('country'){{ $message }}@enderror</span>
                    </div>

                    <div class="mt-3 col-span-1">
                        <label class="text-gray-400 mb-1 ml-1">Email Address<span class="text-red-600">*</span></label>
                        <input type="text" name="emailAddress" value="{{ old('emailAddress') }}" class="border {{ $errors->has('emailAddress') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('emailAddress'){{ $message }}@enderror</span>
                    </div>

                    <div class="mt-3 col-span-1">
                        <label class="text-gray-400 mb-1 ml-1">Contact Number<span class="text-red-600">*</span></label>
                        <input type="text" name="contactNumber" placeholder="(e.g. 09123456789)" value="{{ old('contactNumber') }}" class="border {{ $errors->has('contactNumber') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('contactNumber'){{ $message }}@enderror</span>
                    </div>

                    <div class="mt-3 col-span-1">
                        <label class="text-gray-400 mb-1 ml-1">Landline Number</label>
                        <input type="text" name="landlineNumber" placeholder="(e.g. 81234567)" value="{{ old('landlineNumber') }}" class="border {{ $errors->has('landlineNumber') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('landlineNumber'){{ $message }}@enderror</span>
                    </div>
                </div>



                <h3 class="text-xl font-semibold">Make your Restaurant to be known</h3>
                <div class="grid grid-cols-2 gap-x-5 mt-5">
                    <div class="col-span-full">
                        <label class="text-gray-400 mb-1 ml-1">Restaurant Name<span class="text-red-600">*</span></label>
                        <input type="text" name="rName" value="{{ old('rName') }}" class="border {{ $errors->has('rName') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('rName'){{ $message }}@enderror</span><br>
                    </div>
                    <div class="col-span-full">
                        <label class="text-gray-400 mb-1 ml-1">Restaurant Branch<span class="text-red-600">*</span></label>
                        <input type="text" name="rBranch" value="{{ old('rBranch') }}" class="border {{ $errors->has('rBranch') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('rBranch'){{ $message }}@enderror</span><br>
                    </div>
                    <div class="col-span-full">
                        <label class="text-gray-400 mb-1 ml-1">Address<span class="text-red-600">*</span></label>
                        <input type="text" name="rAddress" value="{{ old('rAddress') }}" class="border {{ $errors->has('rAddress') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('rAddress'){{ "Address is required" }}@enderror</span><br>
                    </div>
                    <div class="col-span-1">
                        <label class="text-gray-400 mb-1 ml-1">City<span class="text-red-600">*</span></label>
                        <input type="text" name="rCity" value="{{ old('rCity') }}" class="border {{ $errors->has('rCity') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('rCity'){{ $message }}@enderror</span><br>
                    </div>
                    <div class="col-span-1">
                        <label class="text-gray-400 mb-1 ml-1">Postal/Zip Code<span class="text-red-600">*</span></label>
                        <input type="text" name="rPostalCode" value="{{ old('rPostalCode') }}" class="border {{ $errors->has('rPostalCode') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('rPostalCode'){{ $message }}@enderror</span><br>
                    </div>
                    <div class="col-span-1">
                        <label class="text-gray-400 mb-1 ml-1">State/Province<span class="text-red-600">*</span></label>
                        <input type="text" name="rState" value="{{ old('rState') }}" class="border {{ $errors->has('rState') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('rState'){{ $message }}@enderror</span><br>
                    </div>
                    <div class="col-span-1">
                        <label class="text-gray-400 mb-1 ml-1">Country<span class="text-red-600">*</span></label>
                        <input type="text" name="rCountry" value="{{ old('rCountry') }}" class="border {{ $errors->has('rCountry') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('rCountry'){{ $message }}@enderror</span><br>
                    </div>
                </div>

                <h3 class="text-xl font-semibold">Validate your Restaurant</h3>
                <p class="text-xs text-gray-400 italic mt-2"><i class="fas fa-exclamation-circle mr-1"></i>All files must be in pdf format</p>

                <div class="grid grid-cols-2 gap-y-3 mt-5 items-center">
                    <label class="col-span-1">BIR Certificate of Registration:</label>
                    <input type="file" accept="application/pdf" class="col-span-1" name="bir">
                    <span class="col-span-full text-red-600 italic text-sm ">@error('bir'){{ $message }}@enderror</span>
                </div>

                
                <div class="grid grid-cols-2 gap-y-3 mt-5 items-center">
                    <label class="col-span-1">DTI Business Registration:</label>
                    <input type="file" accept="application/pdf" class="col-span-1" name="dti">
                    <span class="col-span-full text-red-600 italic text-sm">@error('dti'){{ $message }}@enderror</span>
                </div>

                
                <div class="grid grid-cols-2 gap-y-3 mt-5 items-center">
                    <label class="col-span-1">Mayor’s Permit:</label>
                    <input type="file" accept="application/pdf" class="col-span-1"" name="mayorsPermit">
                    <span class="col-span-full text-red-600 italic text-sm">@error('mayorsPermit'){{ $message }}@enderror</span>
                </div>

                
                <div class="grid grid-cols-2 gap-y-3 mt-5 items-center">
                    <label class="col-span-1">Owner/Staff Valid ID:</label>
                    <input type="file" accept="application/pdf" class="col-span-1"" name="staffValidId">
                    <span class="col-span-full text-red-600 italic text-sm">@error('staffValidId'){{ $message }}@enderror</span>
                </div>

                <div class="mt-6 font-Roboto">
                    <div class="float-right">
                        <button type="submit" class="text-submitButton font-semibold">Submit <i class="fas fa-chevron-right ml-2"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>