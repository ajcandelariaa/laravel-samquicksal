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
            <h3 class="text-xl font-semibold">Data Privacy Policy</h3>
            <div class="overflow-y-auto h-56 border-multiStepBoxBorder border-multiStepBoxColor mt-5 mb-5">
                <p class="px-3 py-2 text-justify text-sm">Privacy Policy for Alt Wav <br><br>
                    At Samquicksal, accessible at samquicksal.com, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by Samquicksal and how we use it. <br><br>
                    
                    If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us through email at samquicksal@gmail.com <br><br>
                    
                    This privacy policy applies only to our online activities and is valid for visitors to our website with regards to the information that they shared and/or collect in Samquicksal. This policy is not applicable to any information collected offline or via channels other than this website. <br><br>
                    
                    Consent <br><br>
                    
                    By using our website, you hereby consent to our Privacy Policy and agree to its terms. <br><br>
                    
                    Information we collect <br><br>
                    
                    The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information. <br><br>
                    
                    If you contact us directly, we may receive additional information about you such as your name, email address, phone number, the contents of the message and/or attachments you may send us, and any other information you may choose to provide. <br><br>
                    
                    When you register for an Account, we may ask for your contact information, including items such as name, company name, address, email address, and telephone number. <br><br>
                    
                    How we use your information <br><br>
                    
                    We use the information we collect in various ways, including to: <br><br>
                    
                    Provide, operate, and maintain our website <br>
                    Improve, personalize, and expand our website <br>
                    Understand and analyze how you use our website <br>
                    Develop new products, services, features, and functionality <br>
                    Communicate with you, either directly or through one of our partners, including for customer service, to provide you with updates and other information relating to the website, and for marketing and promotional purposes <br>
                    Send you emails <br>
                    Find and prevent fraud <br>
                    Log Files <br><br>
                    
                    Samquicksal follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this and a part of hosting services' analytics. The information collected by log files include internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks. These are not linked to any information that is personally identifiable. The purpose of the information is for analyzing trends, administering the site, tracking users' movement on the website, and gathering demographic information. <br><br>
                    
                    Cookies and Web Beacons <br>
                    Like any other website, Samquicksal uses ‘cookies'. These cookies are used to store information including visitors' preferences, and the pages on the website that the visitor accessed or visited. The information is used to optimize the users' experience by customizing our web page content based on visitors' browser type and/or other information. <br><br>
                    
                    DoubleClick DART Cookie <br>
                    Google is one of a third-party vendor on our site. It also uses cookies, known as DART cookies, to serve ads to our site visitors based upon their visit to www.website.com and other sites on the internet. However, visitors may choose to decline the use of DART cookies by visiting the Google ad and content network Privacy Policy at the following URL – https://policies.google.com/technologies/ads. <br><br>
                    
                    Some of advertisers on our site may use cookies and web beacons. Our advertising partners are listed below. Each of our advertising partners has their own Privacy Policy for their policies on user data. For easier access, we hyperlinked to their Privacy Policies below. <br><br>
                    
                    Google <br><br>
                    
                    https://policies.google.com/technologies/ads <br><br>
                    
                    Advertising Partners Privacy Policies <br><br>
                    
                    You may consult this list to find the Privacy Policy for each of the advertising partners of Samquicksal. <br><br>
                    
                    Third-party ad servers or ad networks uses technologies like cookies, JavaScript, or Web Beacons that are used in their respective advertisements and links that appear on Samquicksal, which are sent directly to users' browser. They automatically receive your IP address when this occurs. These technologies are used to measure the effectiveness of their advertising campaigns and/or to personalize the advertising content that you see on websites that you visit. <br><br>
                    
                    Note that Samquicksal has no access to or control over these cookies that are used by third-party advertisers. <br><br>
                    
                    Third-Party Privacy Policies <br><br>
                    
                    Samquicksal's Privacy Policy does not apply to other advertisers or websites. Thus, we are advising you to consult the respective Privacy Policies of these third-party ad servers for more detailed information. It may include their practices and instructions about how to opt-out of certain options. You may find a complete list of these Privacy Policies and their links here: Privacy Policy Links. <br><br>
                    
                    You can choose to disable cookies through your individual browser options. To know more detailed information about cookie management with specific web browsers, it can be found at the browsers' respective websites. What Are Cookies? <br><br>
                    
                    CCPA Privacy Policy (Do Not Sell My Personal Information) <br><br>
                    
                    Under the CCPA, among other rights, California consumers have the right to: <br><br>
                    
                    Request that a business that collects a consumer's personal data disclose the categories and specific pieces of personal data that a business has collected about consumers. <br><br>
                    
                    Request that a business delete any personal data about the consumer that a business has collected. <br><br>
                    
                    Request that a business that sells a consumer's personal data, not sell the consumer's personal data. <br><br>
                    
                    If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us. <br><br>
                    
                    GDPR Privacy Policy (Data Protection Rights) <br><br>
                    
                    We would like to make sure you are fully aware of all of your data protection rights. Every user is entitled to the following: <br><br>
                    
                    The right to access – You have the right to request copies of your personal data. We may charge you a small fee for this service. <br><br>
                    
                    The right to rectification – You have the right to request that we correct any information you believe is inaccurate. You also have the right to request that we complete the information you believe is incomplete. <br><br>
                    
                    The right to erasure – You have the right to request that we erase your personal data, under certain conditions. <br><br>
                    
                    The right to restrict processing – You have the right to request that we restrict the processing of your personal data, under certain conditions. <br><br>
                    
                    The right to object to processing – You have the right to object to our processing of your personal data, under certain conditions. <br><br>
                    
                    The right to data portability – You have the right to request that we transfer the data that we have collected to another organization, or directly to you, under certain conditions. <br><br>
                    
                    If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us. <br><br>
                    
                    Children's Information <br><br>
                    
                    Another part of our priority is adding protection for children while using the internet. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity. <br><br>
                    
                    Samquicksal does not knowingly collect any Personal Identifiable Information from children under the age of 13. If you think that your child provided this kind of information on our website, we strongly encourage you to contact us immediately and we will do our best efforts to promptly remove such information from our records.</p>
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
