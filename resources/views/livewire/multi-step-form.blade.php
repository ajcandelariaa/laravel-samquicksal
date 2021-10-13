<div class="w-3/5 mx-auto pt-10 pb-12">
    <form wire:submit.prevent="register" enctype="multipart/form-data">
        <div class="relative">
            <div class="flex justify-between mb-10 font-semibold ">
                <div class="px-5 py-3 z-10 rounded-xl {{ $currentStep == 1 ? 'bg-submitButton text-white border-2 border-submitButton' : 'border-2 border-multiStepBoxColor text-multiStepBoxColor bg-white' }}">1</div>
                <div class="px-5 py-3 z-10 rounded-xl {{ $currentStep == 2 ? 'bg-submitButton text-white border-2 border-submitButton' : 'border-2 border-multiStepBoxColor text-multiStepBoxColor bg-white' }}">2</div>
                <div class="px-5 py-3 z-10 rounded-xl {{ $currentStep == 3 ? 'bg-submitButton text-white border-2 border-submitButton' : 'border-2 border-multiStepBoxColor text-multiStepBoxColor bg-white' }}">3</div>
                <div class="px-5 py-3 z-10 rounded-xl {{ $currentStep == 4 ? 'bg-submitButton text-white border-2 border-submitButton' : 'border-2 border-multiStepBoxColor text-multiStepBoxColor bg-white' }}">4</div>
                <div class="px-5 py-3 z-10 rounded-xl {{ $currentStep == 5 ? 'bg-submitButton text-white border-2 border-submitButton' : 'border-2 border-multiStepBoxColor text-multiStepBoxColor bg-white' }}">5</div>
            </div>
            <div class="absolute top-2/4 border-multiStepBoxBorder border-multiStepBoxColor w-full z-0"></div>
        </div>
        @if ($currentStep == 1)
            {{-- STEP 1 --}}
            <h3 class="text-xl font-semibold">Data Privacy Agreement</h3>
            <div class="overflow-y-auto h-56 border-multiStepBoxBorder border-multiStepBoxColor mt-5 mb-5">
                <p class="px-3 py-2 text-justify">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Optio eos, molestiae facere numquam suscipit voluptatem autem tempora pariatur libero, distinctio ad consectetur eveniet? Quod eveniet quis enim fuga, eligendi harum? Lorem ipsum, dolor sit amet consectetur adipisicing elit. Optio eos, molestiae facere numquam suscipit voluptatem autem tempora pariatur libero, distinctio ad consectetur eveniet? Quod eveniet quis enim fuga, eligendi harum? Lorem ipsum, dolor sit amet consectetur adipisicing elit. Optio eos, molestiae facere numquam suscipit voluptatem autem tempora pariatur libero, distinctio ad consectetur eveniet? Quod eveniet quis enim fuga, eligendi harum?</p>
            </div>
            <input type="checkbox" wire:model="acceptLicense" class="cursor-pointer mr-2 font-Roboto"> I agree to terms and conditions <span class="text-red-600">*</span>
            <div class="float-right">
                <button type="button" wire:click="increaseStep()" class="font-Roboto text-right text-submitButton font-semibold">Next <i class="fas fa-chevron-right ml-2"></i></button>
            </div>
            <br>
            <span class="mt-2 text-red-600 italic text-sm">@error('acceptLicense'){{ "Please check the terms and conditions first" }}@enderror</span>
        @endif
        
        @if ($currentStep == 2)
            {{-- STEP 2 --}}
            <h3 class="text-xl font-semibold">Tell us about yourself</h3>
            <div class="mt-5">
                <div class="">
                    <label class="text-gray-400 mb-1 ml-1">First Name <span class="text-red-600">*</span></label>
                    <input type="text" wire:model="fname" value="{{ old('fname') }}" class="border {{ $errors->has('fname') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none ">
                        <span class=" text-red-600 italic text-sm">@error('fname'){{ $message }}@enderror</span>
                    
                        
                </div>

                <div class="mt-3">
                    <label class="text-gray-400 mb-1 ml-1">Middle Name</label>
                    <input type="text" wire:model="mname" value="{{ old('mname') }}" class="border {{ $errors->has('mname') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                        <span class="mt-2 text-red-600 italic text-sm">@error('mname'){{ $message }}@enderror</span>
              
                    
                </div>

                <div class="mt-3">
                    <label class="text-gray-400 mb-1 ml-1">Last Name <span class="text-red-600">*</span></label>
                    <input type="text" wire:model="lname" value="{{ old('lname') }}" class="border {{ $errors->has('lname') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('lname'){{ $message }}@enderror</span>
                </div>

                <div class="mt-3">
                    <label class="text-gray-400 mb-1 ml-1">Role<span class="text-red-600">*</span></label>
                    <select wire:model="role" class="border rounded-lg w-full py-1 px-2 text-sm text-gray-500 focus:outline-none {{ $errors->has('role') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
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
                        <input type="date" wire:model="birthDate" value="{{ old('birthDate') }}" class="border rounded-lg w-full py-1 px-2 text-sm text-gray-500 focus:outline-none {{ $errors->has('birthDate') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                        <span class="mt-2 text-red-600 italic text-sm">@error('birthDate'){{ $message }}@enderror</span>
                    </div>
                    <div class="col-span-1">
                        <label class="text-gray-400 mb-1 ml-1">Gender<span class="text-red-600">*</span></label>
                        <select wire:model="gender" class="border rounded-lg w-full py-1 px-2 text-sm text-gray-500 focus:outline-none {{ $errors->has('gender') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }}">
                            <option value="">Please select your gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Female">Others</option>
                        </select>
                        <span class="mt-2 text-red-600 italic text-sm">@error('gender'){{ $message }}@enderror</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 font-Roboto">
                <div class="float-left">
                    <button type="button" wire:click="decreaseStep()" class="text-submitButton font-semibold"><i class="fas fa-chevron-left ml-2"></i> Previous</button>
                </div>
                
                <div class="float-right">
                    <button type="button" wire:click="increaseStep()" class="text-submitButton font-semibold">Next <i class="fas fa-chevron-right ml-2"></i></button>
                </div>
            </div>
        @endif

        @if ($currentStep == 3)
            {{-- STEP 3 --}}
            <h3 class="text-xl font-semibold">How we may contact you?</h3>

            <div class="grid grid-cols-2 gap-x-5 mt-5">
                <div class="col-span-full">
                    <label class="text-gray-400 mb-1 ml-1">Address<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="address" value="{{ old('address') }}" class="border {{ $errors->has('address') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('address'){{ $message }}@enderror</span>
                </div>

                <div class="mt-3 col-span-full">
                    <label class="text-gray-400 mb-1 ml-1">City<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="city" value="{{ old('city') }}" class="border {{ $errors->has('city') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('city'){{ $message }}@enderror</span>
                </div>

                <div class="mt-3 col-span-1">
                    <label class="text-gray-400 mb-1 ml-1">Postal/Zip Code<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="postalCode" value="{{ old('postalCode') }}" class="border {{ $errors->has('postalCode') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('postalCode'){{ $message }}@enderror</span>
                </div>

                <div class="mt-3 col-span-1">
                    <label class="text-gray-400 mb-1 ml-1">State/Province<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="state" value="{{ old('state') }}" class="border {{ $errors->has('state') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('state'){{ $message }}@enderror</span>
                </div>

                <div class="mt-3 col-span-1">
                    <label class="text-gray-400 mb-1 ml-1">Country<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="country" value="{{ old('country') }}" class="border {{ $errors->has('country') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('country'){{ $message }}@enderror</span>
                </div>

                <div class="mt-3 col-span-1">
                    <label class="text-gray-400 mb-1 ml-1">Email Address<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="emailAddress" value="{{ old('emailAddress') }}" class="border {{ $errors->has('emailAddress') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('emailAddress'){{ $message }}@enderror</span>
                </div>

                <div class="mt-3 col-span-1">
                    <label class="text-gray-400 mb-1 ml-1">Contact Number<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="contactNumber" placeholder="(e.g. 09123456789)" value="{{ old('contactNumber') }}" class="border {{ $errors->has('contactNumber') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('contactNumber'){{ $message }}@enderror</span>
                </div>

                <div class="mt-3 col-span-1">
                    <label class="text-gray-400 mb-1 ml-1">Landline Number</label>
                    <input type="text" wire:model="landlineNumber" placeholder="(e.g. 81234567)" value="{{ old('landlineNumber') }}" class="border {{ $errors->has('landlineNumber') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('landlineNumber'){{ $message }}@enderror</span>
                </div>
            </div>

            <div class="mt-6 font-Roboto">
                <div class="float-left">
                    <button type="button" wire:click="decreaseStep()" class="text-submitButton font-semibold"><i class="fas fa-chevron-left ml-2"></i> Previous</button>
                </div>
                
                <div class="float-right">
                    <button type="button" wire:click="increaseStep()" class="text-submitButton font-semibold">Next <i class="fas fa-chevron-right ml-2"></i></button>
                </div>
            </div>
        @endif

        @if ($currentStep == 4)
            {{-- STEP 4 --}}
            <h3 class="text-xl font-semibold">Make your Restaurant to be known</h3>

            <div class="grid grid-cols-2 gap-x-5 mt-5">
                <div class="col-span-full">
                    <label class="text-gray-400 mb-1 ml-1">Restaurant Name<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="rName" value="{{ old('rName') }}" class="border {{ $errors->has('rName') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('rName'){{ $message }}@enderror</span><br>
                </div>
                <div class="col-span-full">
                    <label class="text-gray-400 mb-1 ml-1">Restaurant Branch<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="rBranch" value="{{ old('rBranch') }}" class="border {{ $errors->has('rBranch') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('rBranch'){{ $message }}@enderror</span><br>
                </div>
                <div class="col-span-full">
                    <label class="text-gray-400 mb-1 ml-1">Address<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="rAddress" value="{{ old('rAddress') }}" class="border {{ $errors->has('rAddress') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('rAddress'){{ "Address is required" }}@enderror</span><br>
                </div>
                <div class="col-span-1">
                    <label class="text-gray-400 mb-1 ml-1">City<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="rCity" value="{{ old('rCity') }}" class="border {{ $errors->has('rCity') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('rCity'){{ $message }}@enderror</span><br>
                </div>
                <div class="col-span-1">
                    <label class="text-gray-400 mb-1 ml-1">Postal/Zip Code<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="rPostalCode" value="{{ old('rPostalCode') }}" class="border {{ $errors->has('rPostalCode') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('rPostalCode'){{ $message }}@enderror</span><br>
                </div>
                <div class="col-span-1">
                    <label class="text-gray-400 mb-1 ml-1">State/Province<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="rState" value="{{ old('rState') }}" class="border {{ $errors->has('rState') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('rState'){{ $message }}@enderror</span><br>
                </div>
                <div class="col-span-1">
                    <label class="text-gray-400 mb-1 ml-1">Country<span class="text-red-600">*</span></label>
                    <input type="text" wire:model="rCountry" value="{{ old('rCountry') }}" class="border {{ $errors->has('rCountry') ? 'border-red-600 focus:border-red-600' : 'focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none">
                    <span class="mt-2 text-red-600 italic text-sm">@error('rCountry'){{ $message }}@enderror</span><br>
                </div>
            </div>
            
            <div class="mt-6 font-Roboto">
                <div class="float-left">
                    <button type="button" wire:click="decreaseStep()" class="text-submitButton font-semibold"><i class="fas fa-chevron-left ml-2"></i> Previous</button>
                </div>
                
                <div class="float-right">
                    <button type="button" wire:click="increaseStep()" class="text-submitButton font-semibold">Next <i class="fas fa-chevron-right ml-2"></i></button>
                </div>
            </div>
        @endif

        @if ($currentStep == 5)
            <h3 class="text-xl font-semibold">Validate your Restaurant</h3>
            <p class="text-xs text-gray-400 italic mt-2"><i class="fas fa-exclamation-circle mr-1"></i>All files must be in pdf format</p>

            <div class="grid grid-cols-2 gap-y-3 mt-5 items-center">
                <label class="col-span-1">BIR Certificate of Registration:</label>
                <input type="file" accept="application/pdf" class="col-span-1" wire:model="bir">
                <span class="col-span-full text-red-600 italic text-sm ">@error('bir'){{ $message }}@enderror</span>
            </div>

            
            <div class="grid grid-cols-2 gap-y-3 mt-5 items-center">
                <label class="col-span-1">DTI Business Registration:</label>
                <input type="file" accept="application/pdf" class="col-span-1" wire:model="dti">
                <span class="col-span-full text-red-600 italic text-sm">@error('dti'){{ $message }}@enderror</span>
            </div>

            
            <div class="grid grid-cols-2 gap-y-3 mt-5 items-center">
                <label class="col-span-1">Mayor’s Permit:</label>
                <input type="file" accept="application/pdf" class="col-span-1"" wire:model="mayorsPermit">
                <span class="col-span-full text-red-600 italic text-sm">@error('mayorsPermit'){{ $message }}@enderror</span>
            </div>

            
            <div class="grid grid-cols-2 gap-y-3 mt-5 items-center">
                <label class="col-span-1">Owner/Staff Valid ID:</label>
                <input type="file" accept="application/pdf" class="col-span-1"" wire:model="staffValidId">
                <span class="col-span-full text-red-600 italic text-sm">@error('staffValidId'){{ $message }}@enderror</span>
            </div>

            <div class="mt-6 font-Roboto">
                <div class="float-left">
                    <button type="button" wire:click="decreaseStep()" class="text-submitButton font-semibold"><i class="fas fa-chevron-left ml-2"></i> Previous</button>
                </div>
                
                <div class="float-right">
                    <button type="submit" class="text-submitButton font-semibold">Submit <i class="fas fa-chevron-right ml-2"></i></button>
                </div>
            </div>
        @endif
    </form>
</div>
