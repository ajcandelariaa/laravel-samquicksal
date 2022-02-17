@extends('admin.app2')

@section('content')
<div class="w-full restaurant-account">
    <div class="bg-gradient-to-r from-adminViewAccountHeaderColor to-adminViewAccountHeaderColor2">
        <div class="text-base ml-12 py-3 font-bold uppercase text-center">Restaurant Information</div>
    </div>
    <div class="relative w-full h-56">
        @if ($account->rLogo == null)
            <div style="background-image: linear-gradient(rgba(255, 226, 226, 0.75), rgba(255, 226, 226, 0.75)), url({{asset('images/resto-default.png')}}); background-repeat: no-repeat; background-size: cover; background-position: center;" class="h-full"></div>
        @else
            <div style="background-image: linear-gradient(rgba(255, 226, 226, 0.75), rgba(255, 226, 226, 0.75)), url({{asset('uploads/restaurantAccounts/logo/'.$account->id.'/'.$account->rLogo)}}); background-repeat: no-repeat; background-size: cover; background-position: center;" class="h-full"></div>
        @endif
        
        <div class="absolute top-0 left-headerLogoLeftMargin h-full">
            <div class="grid grid-cols-1 items-center h-full">
                <div class="col-span-1 grid grid-cols-2 items-center">
                    @if ($account->rLogo == null)
                        <img src="{{ asset('images/resto-default.png') }}" alt="accountImage" class="w-44 h-44">
                    @else
                        <img src="{{ asset('uploads/restaurantAccounts/logo/'.$account->id.'/'.$account->rLogo) }}" alt="accountImage" class="w-44 h-44">
                    @endif
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
                <div class="col-span-4 font-bold mb-2">
                    @if ($account->landlineNumber != null)
                        {{ $account->landlineNumber }}
                    @else
                        N/A
                    @endif
                </div>
                
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
                @foreach ($storeHours as $storeHour)
                    @php
                        $days = explode(",", $storeHour->days)
                    @endphp
                    <div class="bg-adminViewAccountHeaderColor2 w-full rounded-xl px-6 py-3 mb-5">
                        <div class="w-full mx-auto mb-3">
                                <div class="tr col-span-1 rounded-lg p-2">
                                    <div hidden class="td">{{ $storeHour->openingTime }}</div>
                                    <div hidden class="td">{{ $storeHour->closingTime }}</div>
                                    <div hidden class="td">{{ $storeHour->id }}</div>
                                    <div hidden class="col-span-1 {{ (in_array('SU', $days)) ? 'text-submitButton td' : 'text-white' }}">SU</div>
                                    <div hidden class="col-span-1 {{ (in_array('MO', $days)) ? 'text-submitButton td' : 'text-white' }}">MO</div>
                                    <div hidden class="col-span-1 {{ (in_array('TU', $days)) ? 'text-submitButton td' : 'text-white' }}">TU</div>
                                    <div hidden class="col-span-1 {{ (in_array('WE', $days)) ? 'text-submitButton td' : 'text-white' }}">WE</div>
                                    <div hidden class="col-span-1 {{ (in_array('TH', $days)) ? 'text-submitButton td' : 'text-white' }}">TH</div>
                                    <div hidden class="col-span-1 {{ (in_array('FR', $days)) ? 'text-submitButton td' : 'text-white' }}">FR</div>
                                    <div hidden class="col-span-1 {{ (in_array('SA', $days)) ? 'text-submitButton td' : 'text-white' }}">SA</div>
        
                                    <div class="bg-adminViewAccountHeaderColor2 p-4 rounded-lg">
                                        <div class="grid grid-cols-7 rounded-full bg-sundayToSaturdayBoxColor p-1 text-xs text-center">
                                            <div class="col-span-1 {{ (in_array('SU', $days)) ? 'text-submitButton' : 'text-white' }}">SU</div>
                                            <div class="col-span-1 {{ (in_array('MO', $days)) ? 'text-submitButton' : 'text-white' }}">MO</div>
                                            <div class="col-span-1 {{ (in_array('TU', $days)) ? 'text-submitButton' : 'text-white' }}">TU</div>
                                            <div class="col-span-1 {{ (in_array('WE', $days)) ? 'text-submitButton' : 'text-white' }}">WE</div>
                                            <div class="col-span-1 {{ (in_array('TH', $days)) ? 'text-submitButton' : 'text-white' }}">TH</div>
                                            <div class="col-span-1 {{ (in_array('FR', $days)) ? 'text-submitButton' : 'text-white' }}">FR</div>
                                            <div class="col-span-1 {{ (in_array('SA', $days)) ? 'text-submitButton' : 'text-white' }}">SA</div>
                                        </div>
                                    </div>
                                    <div class="mt-3 grid grid-cols-3 text-xs text-center items-center w-9/12 mx-auto">
                                        <div class="text-submitButton">Opening</div>
                                        <div class="text-submitButton">:</div>
                                        <div>{{ $storeHour->openingTime }}</div>
                                    </div>
                                    <div class="tr mt-3 grid grid-cols-3 text-xs text-center items-center w-9/12 mx-auto">
                                        <div class="text-submitButton">Closing</div>
                                        <div class="text-submitButton">:</div>
                                        <div>{{ $storeHour->closingTime }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endforeach
                <div class="grid grid-cols-5 mt-8 text-sm">
                    <div class="col-span-2 text-left text-submitButton"><i class="fas fa-file w-7 text-center mr-1"></i>Status</div>
                    <div class="col-span-1 text-center text-submitButton">:</div>
                    <div class="col-span-2 font-bold mb-4 text-right">{{ $account->status }}</div>
                    
                    <div class="col-span-2 text-left text-submitButton"><i class="fas fa-store w-7 text-center mr-1"></i>Store</div>
                    <div class="col-span-1 text-center text-submitButton">:</div>
                    <div class="col-span-2 font-bold mb-4 text-right">{{ $schedToday }}</div>
                    
                    <div class="col-span-2 text-left text-submitButton"><i class="fas fa-ethernet w-7 text-center mr-1"></i>Number of Tables</div>
                    <div class="col-span-1 text-center text-submitButton">:</div>
                    <div class="col-span-2 font-bold mb-4 text-right">{{ $account->rNumberOfTables }}</div>
                    
                    <div class="col-span-2 text-left text-submitButton"><i class="fas fa-chair w-7 text-center mr-1"></i>Total Chairs</div>
                    <div class="col-span-1 text-center text-submitButton">:</div>
                    <div class="col-span-2 font-bold mb-4 text-right">{{ $totalNumberOfChairs }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection