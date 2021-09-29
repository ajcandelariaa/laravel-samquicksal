@extends('admin.app2')

@section('content')
<div class="bg-gray-200 w-full restaurant-applicant">
    <div class="overlay"></div>
    <div class="overlay2"></div>
    @if ($applicant->status == "Declined")
        <div class="container w-11/12 mx-auto">
            <div class="mt-10 w-full flex justify-end gap-x-4">
                <a href="/admin/restaurant-applicants/delete/{{ $applicant->id }}" class="text-sm text-white bg-red-600 rounded-xl py-1 px-7 hover:bg-red-700 shadow-adminDownloadButton">Delete this Form</a>
            </div>
        </div>
    @else
        @if ($applicant->status == "Approved")
            <div class="container w-11/12 mx-auto mb-10">
                <div class="mt-10 w-full flex justify-end gap-x-4">
                    <a class="text-sm bg-gray-500 rounded-xl py-1 px-7 shadow-adminDownloadButton">Decline</a>
                    <a class="text-sm bg-gray-500 rounded-xl py-1 px-7 shadow-adminDownloadButton">Approve</a>
                </div>
            </div>
        @else
            <div class="container w-11/12 mx-auto mb-10">
                <div class="mt-10 w-full flex justify-end gap-x-4">
                    {{-- <a href="/admin/restaurant-applicants/decline/{{ $applicant->id }}/{{ $applicant->emailAddress }}" class="text-sm bg-declineButton rounded-xl py-1 px-7 hover:bg-yellow-400 shadow-adminDownloadButton">Decline</a>
                    <a href="/admin/restaurant-applicants/approve/{{ $applicant->id }}/{{ $applicant->emailAddress }}" class="text-sm bg-approveButton rounded-xl py-1 px-7 hover:bg-green-400 shadow-adminDownloadButton">Approve</a> --}}
                    <button id="btn-declined" class="text-sm bg-declineButton rounded-xl py-1 px-7 hover:bg-yellow-400 shadow-adminDownloadButton">Decline</button>
                    <button id="btn-approved" class="text-sm bg-approveButton rounded-xl py-1 px-7 hover:bg-green-400 shadow-adminDownloadButton">Approve</button>
                </div>
            </div>

            {{-- DECLINE POP UP FORM --}}
            <div class="popup">
                <div class="close-btn"><i class="fas fa-times"></i></div>
                <h1 class="mt-2 text-xl font-bold">Please check what are the applicant’s missing detail: </h1>
                <form action="/admin/restaurant-applicants/decline" method="POST" id='declinedForm'>
                    @csrf
                    <input type="text" name="applicantId" hidden value="{{ $applicant->id }}">
                    <input type="text" name="applicantEmail" hidden value="{{ $applicant->emailAddress }}">
                    <input type="text" name="applicantName" hidden value="{{ $applicant->fname.' '.$applicant->lname }}">
                    <div class="grid grid-cols-4 mt-5 w-11/12 mx-auto">
                        <div class="col-span-2">
                            <h3 class="font-bold mb-2">Owner / Staff Information</h3>
                            <div class="grid grid-cols-2 w-full">
                                <div><input type="checkbox" name="checkbox[]" value="First Name"> First Name</div>
                                <div><input type="checkbox" name="checkbox[]" value="Middle Name"> Middle Name</div>
                                <div><input type="checkbox" name="checkbox[]" value="Last name"> Last name</div>
                                <div><input type="checkbox" name="checkbox[]" value="Address"> Address</div>
                                <div><input type="checkbox" name="checkbox[]" value="City"> City</div>
                                <div><input type="checkbox" name="checkbox[]" value="Postal/Zip Code"> Postal/Zip Code</div>
                                <div><input type="checkbox" name="checkbox[]" value="State/Province"> State/Province</div>
                                <div><input type="checkbox" name="checkbox[]" value="Country"> Country</div>
                                <div><input type="checkbox" name="checkbox[]" value="Role"> Role</div>
                                <div><input type="checkbox" name="checkbox[]" value="Birthdate"> Birthdate</div>
                                <div><input type="checkbox" name="checkbox[]" value="Gender"> Gender</div>
                                <div><input type="checkbox" name="checkbox[]" value="Contact Number"> Contact Number</div>
                                <div><input type="checkbox" name="checkbox[]" value="Landline Number"> Landline Number</div>
                                <div><input type="checkbox" name="checkbox[]" value="Email Address"> Email Address</div>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <h3 class="font-bold mb-2">Restaurant Information</h3>
                            <div><input type="checkbox" name="checkbox[]" value="Restaurant Name"> Name</div>
                            <div><input type="checkbox" name="checkbox[]" value="Restaurant Branch"> Branch</div>
                            <div><input type="checkbox" name="checkbox[]" value="Restaurant Address"> Address</div>
                            <div><input type="checkbox" name="checkbox[]" value="Restaurant City"> City</div>
                            <div><input type="checkbox" name="checkbox[]" value="Restaurant Postal/Zip Code"> Postal/Zip Code</div>
                            <div><input type="checkbox" name="checkbox[]" value="Restaurant State/Province"> State/Province</div>
                            <div><input type="checkbox" name="checkbox[]" value="Restaurant Country"> Country</div>
                        </div>
                        <div class="col-span-1">
                            <h3 class="font-bold mb-2">Documents</h3>
                            <div><input type="checkbox" name="checkbox[]" value="BIR Certficate of Registration"> BIR Certficate of Registration</div>
                            <div><input type="checkbox" name="checkbox[]" value="DTI Business Registration"> DTI Business Registration</div>
                            <div><input type="checkbox" name="checkbox[]" value="Mayor’s Permit"> Mayor’s Permit</div>
                            <div><input type="checkbox" name="checkbox[]" value="Owner/Staff Valid ID"> Owner/Staff Valid ID</div>
                        </div>
                    </div>
                    <div class="text-center mb-5 mt-10">
                        <button class="text-sm bg-submitButton rounded-lg py-1 px-7 hover:bg-red-600 shadow-adminDownloadButton text-white">Submit</button>
                    </div>
                </form>
            </div>

            
            {{-- APPROVED POP UP FORM --}}
            <div class="popup2">
                <div class="close-btn2"><i class="fas fa-times"></i></div>
                <h1 class="mt-2 text-xl font-bold mb-10">Please enter the address to locate the restaurant </h1>
                <p>Restaurant Address: <span class="font-semibold">{{ $applicant->rAddress.', '.$applicant->rCity.', '.$applicant->rState.', '.$applicant->rCountry }}</span></p>
                <form action="/admin/restaurant-applicants/approve" method="POST" id='approvedForm'>
                    @csrf
                    <input type="text" name="applicantId" hidden value="{{ $applicant->id }}">
                    <input type="text" name="applicantLocLat" hidden value="14.601021">
                    <input type="text" name="applicantLocLong" hidden value="120.990050">
                    <div class="grid grid-cols-3 w-full">
                        <div class="my-2 col-span-1">
                            <label>Re-enter Address: </label>
                            <input type="text" name="inputtedAddress" id="inputtedAddress" class="ml-2 border border-gray-300 focus:border-black rounded-md px-2 text-sm text-gray-700 focus:outline-none">
                        </div>
                        <p class="text-multiStepBoxColor col-span-1 mt-2">Latitude: <span class="ml-3">41°24'12.2"N 2°10'26.5"E</span></p>
                        <p class="text-multiStepBoxColor col-span-1 mt-2">Longitude: <span class="ml-3">41°24'12.2"N 2°10'26.5"E</span></p>
                    </div>
                    
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.911375538337!2d120.9864360152883!3d14.604124180904545!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9f8b14eb259%3A0xad4d12caac9a068e!2sFEU%20Institute%20of%20Technology!5e0!3m2!1sen!2sae!4v1632310147062!5m2!1sen!2sae" allowfullscreen="" loading="lazy" class="w-full h-52 border-0"></iframe>
                    <div class="text-center mb-5 mt-5">
                        <button class="text-sm bg-submitButton rounded-lg py-1 px-7 hover:bg-red-600 shadow-adminDownloadButton text-white">Submit</button>
                    </div>
                </form>
            </div>
        @endif
    @endif
    <div class="container w-11/12 mx-auto shadow-xl mb-14">
        
        <div class="bg-gradient-to-r from-adminViewAccountHeaderColor to-white mt-10">
            <div class="text-base ml-12 py-4 font-bold">Restaurant Information</div>
        </div>
        <div class="bg-white">
            <div class="grid grid-cols-2 w-10/12 mx-auto py-14 text-sm gap-y-4">
                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Name: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->rName }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Branch: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->rBranch }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Address: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->rAddress }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">City: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->rCity }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Postal/Zip Code: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->rPostalCode }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">State/Province: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->rState }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Country: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->rCountry }}</p> 
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-r from-adminViewAccountHeaderColor to-white">
            <div class="text-base ml-12 py-4 font-bold">Owner / Staff Information</div>
        </div>
        <div class="bg-white">
            <div class="grid grid-cols-2 w-10/12 mx-auto py-14 text-sm gap-y-4">
                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">First Name: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->fname }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Middle Name: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->mname }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Last Name: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->lname }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Address: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->address }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">City: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->city }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Postal/Zip Code: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->postalCode }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">State/Province: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->state }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Country: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->country }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Role: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->role }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Birthdate: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->birthDate }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Gender: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->gender }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Contact Number: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->contactNumber }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Landline Number: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->landlineNumber }}</p> 
                </div>

                <div class="col-span-1 grid grid-cols-adminViewAccountGridSize">
                    <p class="col-span-1">Email Address: </p>
                    <p class="ml-8 font-bold col-span-1">{{ $applicant->emailAddress }}</p> 
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-r from-adminViewAccountHeaderColor to-white">
            <div class="text-base ml-12 py-4 font-bold">Restaurant Documents</div>
        </div>
        <div class="bg-white">
            <div class="w-10/12 mx-auto py-14 text-sm">
                <div class="grid grid-cols-4 items-center">
                    <p class="col-span-1">BIR Certificate of Registration: </p>
                    <p class="col-span-1 text-gray-400">{{ $applicant->bir }}</p>
                    <a href="/admin/restaurant-applicant/viewFile/{{ $applicant->id }}/{{ $applicant->bir }}" target="_blank" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">View</a>
                    <a href="/admin/restaurant-applicant/downloadFile/{{ $applicant->id }}/{{ $applicant->bir }}" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">Download</a>
                </div>
                <hr class="my-2">
                <div class="grid grid-cols-4 items-center">
                    <p class="col-span-1">DTI Business Registration: </p>
                    <p class="col-span-1 text-gray-400">{{ $applicant->dti }}</p>
                    <a href="/admin/restaurant-applicant/viewFile/{{ $applicant->id }}/{{ $applicant->dti }}" target="_blank" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">View</a>
                    <a href="/admin/restaurant-applicant/downloadFile/{{ $applicant->id }}/{{ $applicant->dti }}" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">Download</a>
                </div>
                <hr class="my-2">
                <div class="grid grid-cols-4 items-center">
                    <p class="col-span-1">Mayor’s Permit: </p>
                    <p class="col-span-1 text-gray-400">{{ $applicant->mayorsPermit }}</p>
                    <a href="/admin/restaurant-applicant/viewFile/{{ $applicant->id }}/{{ $applicant->mayorsPermit }}" target="_blank" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">View</a>
                    <a href="/admin/restaurant-applicant/downloadFile/{{ $applicant->id }}/{{ $applicant->mayorsPermit }}" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">Download</a>
                </div>
                <hr class="my-2">
                <div class="grid grid-cols-4 items-center">
                    <p class="col-span-1">Owner/Staff Valid ID: </p>
                    <p class="col-span-1 text-gray-400">{{ $applicant->staffValidId }}</p>
                    <a href="/admin/restaurant-applicant/viewFile/{{ $applicant->id }}/{{ $applicant->staffValidId }}" target="_blank" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">View</a>
                    <a href="/admin/restaurant-applicant/downloadFile/{{ $applicant->id }}/{{ $applicant->staffValidId }}" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">Download</a>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection