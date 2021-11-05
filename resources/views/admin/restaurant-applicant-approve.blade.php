@extends('admin.app2')

@section('content')
{{-- <div class="bg-gray-200 w-full restaurant-applicant"> --}}
    {{-- APPROVED POP UP FORM --}}
    {{-- <div class="bg-white w-11/12 mx-auto mt-10 p-5"> --}}

    {{-- <h1 class="mt-2 text-xl font-bold mb-10">Please enter the address to locate the restaurant </h1>
    <p>Restaurant Address: <span class="font-semibold">{{ $applicant->rAddress.', '.$applicant->rBranch.', '.$applicant->rCity.', '.$applicant->rState.', '.$applicant->rCountry }}</span></p>
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
            <p class="text-multiStepBoxColor col-span-1 mt-2">Latitude: <span class="ml-3">14.601021</span></p>
            <p class="text-multiStepBoxColor col-span-1 mt-2">Longitude: <span class="ml-3">120.990050</span></p>
        </div>
        
        <div id="map"></div>
            <div id="infowindow-content">
                <span id="place-name" class="title"></span><br/>
                <span id="place-address"></span>
            </div>
        <div class="text-center mb-5 mt-5">
            <button class="text-sm bg-submitButton rounded-lg py-1 px-7 hover:bg-btnHoverColor shadow-adminDownloadButton text-white">Submit</button>
        </div>
    </form>
        <input type="text" name="lname" value="{{ old('lname') }}" class="border rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none"> --}}
    <div class="pac-card" id="pac-card">
        <div>
            <form action="/admin/restaurant-applicants/approve" method="POST">
                @csrf
                <input type="text" name="applicantId" hidden value="{{ $applicant->id }}">
                <div class="grid grid-cols-2 items-center bg-blue-500 py-2">
                    <div id="title">Please enter the address to locate the restaurant</div>
                    <div class="text-right mr-5">
                        <button type="submit" class="text-sm bg-submitButton rounded-lg py-1 px-7  hover:bg-btnHoverColor shadow-adminDownloadButton text-white">Submit</button>
                    </div>
                </div>
                
                <p class="font-Montserrat ml-3 mt-5">Restaurant Address: <span class="font-semibold">{{ $applicant->rAddress.', '.$applicant->rBranch.', '.$applicant->rCity.', '.$applicant->rState.', '.$applicant->rCountry }}</span></p>
    
                <div class="grid grid-cols-2 w-1/2">
                    <div>
                        <label class="text-sm ml-3 mr-2">Latitude: </label>
                        <input type="text" id="cityLat" name="applicantLocLat" class="bg-gray-100 border rounded-md px-2 text-sm mt-2 text-gray-700 focus:outline-none" readonly/>
                        @error('applicantLocLat') <br> <span class="mt-2 ml-3 text-red-600 italic text-xs">{{ $message }}</span> @enderror
                        
                    </div>
                    <div>
                        <label class="text-sm ml-3 mr-2">Longitude: </label>
                        <input type="text" id="cityLong" name="applicantLocLong" class="bg-gray-100 border rounded-md px-2 text-sm mt-2 text-gray-700 focus:outline-none" readonly/>
                        @error('applicantLocLong') <br> <span class="mt-2 ml-3 text-red-600 italic text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
              
              
            </form>
          <div id="type-selector" class="pac-controls">
            <input
              type="radio"
              name="type"
              id="changetype-all"
              checked="checked"
            />
            <label for="changetype-all">All</label>
  
            <input type="radio" name="type" id="changetype-establishment" />
            <label for="changetype-establishment">establishment</label>
  
            <input type="radio" name="type" id="changetype-address" />
            <label for="changetype-address">address</label>
  
            <input type="radio" name="type" id="changetype-geocode" />
            <label for="changetype-geocode">geocode</label>
  
            <input type="radio" name="type" id="changetype-cities" />
            <label for="changetype-cities">(cities)</label>
  
            <input type="radio" name="type" id="changetype-regions" />
            <label for="changetype-regions">(regions)</label>
          </div>
          <br />
          <div id="strict-bounds-selector" class="pac-controls">
            <input type="checkbox" id="use-location-bias" value="" checked />
            <label for="use-location-bias">Bias to map viewport</label>
  
            <input type="checkbox" id="use-strict-bounds" value="" />
            <label for="use-strict-bounds">Strict bounds</label>
          </div>
        </div>
        <div id="pac-container">
          <label class="ml-3 mt-2 text-sm font-Montserrat">Re-enter Address: </label>
          <input id="pac-input" type="text" placeholder="Enter a location" class="border rounded-md w-full py-1 px-2 text-sm text-gray-700 focus:outline-none"/>
        </div>
      </div>
      <div id="map"></div>
      <div id="infowindow-content">
        <span id="place-name" class="title"></span><br />
        <span id="place-address"></span>
      </div>
        
@endsection