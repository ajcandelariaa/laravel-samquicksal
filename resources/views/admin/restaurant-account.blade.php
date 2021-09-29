@extends('admin.app2')

@section('content')
<div class="w-full restaurant-account">
    <div class="bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2">
        <div class="text-base ml-12 py-3 font-bold uppercase text-center">Restaurant Information</div>
    </div>
    <div class="relative w-full h-56">
        <div style="background-image: linear-gradient(rgba(255, 226, 226, 0.75), rgba(255, 226, 226, 0.75)), url({{asset('images/defaultAccountImage.png')}}); background-repeat: no-repeat; background-size: cover; background-position: center;" class="h-full"></div>
        <div class="absolute top-0 left-headerLogoLeftMargin h-full">
            <div class="grid grid-cols-1 items-center h-full">
                <div class="col-span-1 grid grid-cols-2 items-center">
                    <img src="{{ asset('images/defaultAccountImage.png') }}" alt="accountImage" class="w-44">
                    <div>
                        <p class="uppercase font-bold text-4xl">{{ $account->rName }}</p>
                        <p class="text-lg">{{ $account->rBranch }}</p>
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
                <div class="col-span-4 font-bold mb-2">{{ $account->rName }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Branch</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->rBranch }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Location</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->rAddress.', '.$account->rCity.', '.$account->rState.', '.$account->rCountry }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Zip Code</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->rPostalCode }}</div>
            </div>


            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Contact Information</div>
            <div class="grid grid-cols-6 w-10/12 mx-auto mt-5 mb-5">
                <div class="col-span-1 text-left text-submitButton">Phone</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->contactNumber }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Landline</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->landlineNumber }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Email</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->emailAddress }}</div>
            </div>

            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Owner/Staff Information</div>
            <div class="grid grid-cols-6 w-10/12 mx-auto mt-5 mb-5">
                <div class="col-span-1 text-left text-submitButton">Name</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->fname.' '.$account->mname.' '.$account->lname }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Location</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->address.', '.$account->city.', '.$account->state.', '.$account->country }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Zip Code</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->postalCode }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Role</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->role }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Birthdate</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->birthDate }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Gender</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->gender }}</div>
                
                <div class="col-span-1 text-left text-submitButton">Username</div>
                <div class="col-span-1 text-left text-submitButton">:</div>
                <div class="col-span-4 font-bold mb-2">{{ $account->username }}</div>
            </div>


            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Restaurant Documents</div>
            <div class="w-10/12 mx-auto py-14 text-sm">
                <div class="grid grid-cols-2 items-center">
                    <p class="col-span-1">BIR Certificate of Registration: </p>
                    <a href="/admin/restaurant-account/viewFile/{{ $account->id }}/{{ $account->bir }}" target="_blank" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">View</a>
                </div>
                <hr class="my-2">
                <div class="grid grid-cols-2 items-center">
                    <p class="col-span-1">DTI Business Registration: </p>
                    <a href="/admin/restaurant-account/viewFile/{{ $account->id }}/{{ $account->dti }}" target="_blank" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">View</a>
                </div>
                <hr class="my-2">
                <div class="grid grid-cols-2 items-center">
                    <p class="col-span-1">Mayor’s Permit: </p>
                    <a href="/admin/restaurant-account/viewFile/{{ $account->id }}/{{ $account->mayorsPermit }}" target="_blank" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">View</a>
                </div>
                <hr class="my-2">
                <div class="grid grid-cols-2 items-center">
                    <p class="col-span-1">Owner/Staff Valid ID: </p>
                    <a href="/admin/restaurant-account/viewFile/{{ $account->id }}/{{ $account->staffValidId }}" target="_blank" class="col-span-1 justify-self-end bg-gray-200 rounded-xl py-1 px-7 hover:bg-gray-300">View</a>
                </div>
            </div>

        </div>
        
        <div class="col-span-5 bg-white">
            <div class="uppercase font-bold bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2 py-2 text-submitButton pl-5">Restaurant Status</div>
            <div class="container w-10/12 mx-auto mt-5">
                <div class="bg-adminViewAccountHeaderColor2 w-full rounded-xl px-6 py-3">
                    <div class="grid grid-cols-2 w-full gap-x-7">
                        <div class="col-span-1 grid grid-cols-2 w-full">
                            <div class="text-sm text-submitButton">Opens at:</div>
                            <div class="text-sm font-bold">10:00 A.M</div>
                        </div>
                        <div class="col-span-1 grid grid-cols-2 w-full">
                            <div class="text-sm text-submitButton">Closes at:</div>
                            <div class="text-sm font-bold">10:00 P.M</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 mt-5 bg-sundayToSaturdayBoxColor rounded-full w-10/12 text-sm text-white font-bold mx-auto items-center mb-2">
                        <div class="bg-submitButton rounded-full w-full h-full px-2 py-2 text-center">S</div>
                        <div class="rounded-full  w-full h-full px-2 py-2 text-center">M</div>
                        <div class="rounded-full w-full h-full px-2 py-2 text-center">T</div>
                        <div class="bg-submitButton rounded-full w-full h-full px-2 py-2 text-center">W</div>
                        <div class="bg-submitButton rounded-full  w-full h-full px-2 py-2 text-center">T</div>
                        <div class="rounded-full  w-full h-full px-2 py-2 text-center">F</div>
                        <div class="rounded-full  w-full h-full px-2 py-2 text-center">S</div>
                    </div>
                </div>
                <div class="grid grid-cols-5 mt-8 text-sm">
                    <div class="col-span-2 text-left text-submitButton"><i class="fas fa-file mr-3"></i> Status</div>
                    <div class="col-span-1 text-center text-submitButton">:</div>
                    <div class="col-span-2 font-bold mb-4 text-right">{{ $account->status }}</div>
                    
                    <div class="col-span-2 text-left text-submitButton"><i class="fas fa-store mr-3"></i>Store</div>
                    <div class="col-span-1 text-center text-submitButton">:</div>
                    <div class="col-span-2 font-bold mb-4 text-right">{{ "Open" }}</div>
                    
                    <div class="col-span-2 text-left text-submitButton"><i class="fas fa-ethernet mr-3"></i>Number of Tables</div>
                    <div class="col-span-1 text-center text-submitButton">:</div>
                    <div class="col-span-2 font-bold mb-4 text-right">{{ $account->rNumberOfTables }}</div>
                    
                    <div class="col-span-2 text-left text-submitButton"><i class="fas fa-chair mr-3"></i>Chairs per Table</div>
                    <div class="col-span-1 text-center text-submitButton">:</div>
                    <div class="col-span-2 font-bold mb-4 text-right">{{ $account->rCapacityPerTable }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection