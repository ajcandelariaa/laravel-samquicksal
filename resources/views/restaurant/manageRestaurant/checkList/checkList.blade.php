@extends('restaurant.app2')

@section('content')
<div class="container mx-auto store-hours">
    @if (session()->has('published'))
        <script>
            Swal.fire(
                'Restaurant Publish Successfully',
                '',
                'success'
            );
        </script>
    @elseif (session()->has('cantPublished'))
        <script>
            Swal.fire(
                'Cannot be Publish',
                '',
                'error'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto my-10 font-Montserrat">
        <div class="w-full mt-3 shadow-adminDownloadButton items-center bg-manageRestaurantSidebarColorActive pb-10">
            <div class="flex justify-between px-5 py-4 items-center">
                <div class="text-white font-bold uppercase text-xl">Publish Restaurant</div>
            </div>
            <div class="bg-white">
                <div class="grid grid-cols-2 px-2 gap-2 py-2">
                    <div class="col-span-1 bg-manageFoodItemHeaderBgColor py-7">
                        <h2 class="text-xl text-center font-bold">Your Restaurant is about to be Published!</h2>
                        <img src="{{ asset('images/publishLogo.png') }}" alt="publishLogo" class="w-40 mx-auto mt-10">
                        <p class="text-sm w-10/12 mx-auto mt-10">You may want to Publish your restaurant after you fill up the necessary information in the Manage Restaurant. Right? </p>
                        <p class="text-sm w-10/12 mx-auto mt-5">So let’s do quick review before showing it to the public! </p>
                    </div>

                    <div class="col-span-1 row-span-2 bg-manageFoodItemHeaderBgColor py-7">
                        <h2 class="text-xl text-center font-bold">Share your Restaurant to the Public!</h2>
                        <img src="{{ asset('images/publishLogo2.png') }}" alt="publishLogo" class="w-72 mx-auto mt-10">
                        <p class="text-sm text-center font-bold">If changes has been made, you are Good to Go!</p>
                        <p class="text-sm w-10/12 mx-auto font-bold  mt-16">Please note that after you publish your restaurant, changes in Manage Restaurant will be applied and may affect the following:</p>
                        <p class="text-xs w-10/12 mx-auto mt-5 ml-20">1. Upon updating the number of tables and table capacity will only apply within closing hours. Also, all the reservation onwards will be void and it will notify the customers.</p>
                        <p class="text-xs w-10/12 mx-auto mt-2 ml-20">2. In Food Item, Food Set and Order Set you can't edit and delete unless your store is within closing hours or not Visible. Also, setting the status of food iten and food set can be changed within closing hours.</p>
                        <p class="text-xs w-10/12 mx-auto mt-2 ml-20">3. Also, in Order Set if you change the status, delete, or edit. All reserved customers that ordered that Order Set will be void and it will notify the customers.</p>
                        <p class="text-xs w-10/12 mx-auto mt-2 ml-20">4. In Managing the Store Hours you can't edit and delete unless your store is within closing hours. Also, all the reservation onwards will be void and it will notify the customers.</p>
                        <p class="text-xs w-10/12 mx-auto mt-2 ml-20">5. Lastly, be careful when editing and adding unavailable dates because all the customer reservation that have been reserved on that particular time will be void.</p>
                        
                        <div class="text-center mt-16">
                            @if ($restaurant->status == "Unpublished" && $restaurant->verified == "Yes" && $restaurant->rLogo != null && $restaurant->rGcashQrCodeImage != null && $orderSet > 0)
                                <a href="/restaurant/manage-restaurant/checklist/publish" id="btnPublish" class="bg-submitButton py-3 px-14 rounded-lg text-white font-Montserrat hover:bg-darkerSubmitButton hover:text-gray-300 transition duration-200 ease-in-out mt-10">Publish Now</a>
                            @else
                                <button class="bg-manageRestaurantSidebarColor py-3 px-14 rounded-lg text-white font-Montserrat cursor-not-allowed" disabled>Publish Now</button>
                            @endif
                        </div>
                    </div>

                    
                    <div class="col-span-1 bg-manageFoodItemHeaderBgColor py-7">
                        <h2 class="text-xl w-10/12 mx-auto font-bold">Review Your Details Here</h2>
                        <p class="w-10/12 mx-auto mt-7 font-bold">Steps to do:</p>

                        
                        <p class="text-sm w-10/12 mx-auto mt-5 ml-20">
                            @if ($restaurant->rLatitudeLoc != null && $restaurant->rLongitudeLoc != null)
                                <i class="fas fa-check-circle mr-2 text-postedStatus"></i>
                            @else
                                <i class="fas fa-times-circle mr-2 text-adminDeleteFormColor"></i>
                            @endif
                            1. Locate your Restaurant
                        </p>

                        <p class="text-sm w-10/12 mx-auto mt-2 ml-20">
                            @if ($restaurant->verified == "Yes")
                                <i class="fas fa-check-circle mr-2 text-postedStatus"></i>
                            @else
                                <i class="fas fa-times-circle mr-2 text-adminDeleteFormColor"></i>
                            @endif
                            2. Verify your E-mail Address
                        </p>

                        <p class="text-sm w-10/12 mx-auto mt-2 ml-20">
                            @if ($restaurant->rLogo != null)
                                <i class="fas fa-check-circle mr-2 text-postedStatus"></i>
                            @else
                                <i class="fas fa-times-circle mr-2 text-adminDeleteFormColor"></i>
                            @endif
                            3. Change Restaurant Logo
                        </p>

                        <p class="text-sm w-10/12 mx-auto mt-2 ml-20">
                            @if ($restaurant->rGcashQrCodeImage != null)
                                <i class="fas fa-check-circle mr-2 text-postedStatus"></i>
                            @else
                                <i class="fas fa-times-circle mr-2 text-adminDeleteFormColor"></i>
                            @endif
                            4. Upload GCash QR Code Image
                        </p>

                        <p class="text-sm w-10/12 mx-auto mt-2 ml-20">
                            @if ($orderSet > 0)
                                <i class="fas fa-check-circle mr-2 text-postedStatus"></i>
                            @else
                                <i class="fas fa-times-circle mr-2 text-adminDeleteFormColor"></i>
                            @endif
                            5. Create at least one Order Set (Visible and Available)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection