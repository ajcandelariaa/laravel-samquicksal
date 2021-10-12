<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerAccount;
use App\Models\RestaurantAccount;
use App\Models\StoreHour;
use App\Models\UnavailableDate;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public $RESTAURANT_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/logo";
    public $RESTAURANT_NO_IMAGE_PATH = "http://192.168.1.53:8000/images";
    public function convertDays($day){
        if ($day == "MO"){
            return "Monday";
        } else if ($day == "TU"){
            return "Tuesday";
        } else if ($day == "WE"){
            return "Wednesday";
        } else if ($day == "TH"){
            return "Thursday";
        } else if ($day == "FR"){
            return "Friday";
        } else if ($day == "SA"){
            return "Saturday";
        } else if ($day == "SU"){
            return "Sunday";
        } else {
            return "";
        }
    }


    public function getListOfRestaurants(){
        $restaurants = RestaurantAccount::select('id', 'rName', 'rAddress', 'rLogo')->get();
        $finalData = array();
        foreach ($restaurants as $restaurant){
            $getAvailability = "";
            $rSchedule = "";

            $restaurantUnavailableDates = UnavailableDate::select('unavailableDatesDate')->where('restAcc_id', $restaurant->id)->get();
            foreach ($restaurantUnavailableDates as $restaurantUnavailableDate) {
                if($restaurantUnavailableDate->unavailableDatesDate == date("Y-m-d")){
                    $getAvailability = "Closed";
                } else {
                    $getAvailability = "Open";
                }
            }

            if($getAvailability == "Closed"){
                $rSchedule = "Closed Now";
            } else {
                $restaurantStoreHours = StoreHour::where('restAcc_id', $restaurant->id)->get();
                foreach ($restaurantStoreHours as $restaurantStoreHour){
                    foreach (explode(",", $restaurantStoreHour->days) as $day){
                        if($this->convertDays($day) == date('l')){
                            $rSchedule = "Open today at ".$restaurantStoreHour->openingTime." to ".$restaurantStoreHour->closingTime;
                        }
                    }
                }
            }
            if($rSchedule == ""){
                $rSchedule = "Closed Now";
            }

            $finalImageUrl = "";
            if ($restaurant->rLogo == ""){$finalImageUrl = $this->RESTAURANT_NO_IMAGE_PATH.'/defaultAccountImage.png';
                
            } else {
                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
            }

            array_push($finalData, [
                'id' => $restaurant->id,
                'rName' => $restaurant->rName,
                'rAddress' => $restaurant->rAddress,
                'rLogo' => $finalImageUrl,
                'rSchedule' => $rSchedule
            ]);
        }
        return response()->json($finalData);
    }




    public function loginCustomer(Request $request){
        
    }
    public function getRestaurantById($id){
        $restaurants = RestaurantAccount::select(
            'id', 
            'verified', 
            'status', 

            'rName',
            'rBranch',
            'rAddress',
            'rCity',
            'rPostalCode',
            'rState',
            'rCountry',

            'rLatitudeLoc',
            'rLongitudeLoc',

            'rNumberOfTables',
            'rCapacityPerTable',
            'rGcashQrCodeImage',
            'rLogo',

            'rTimeLimit',
        )->where('id', $id)->first();
        return response()->json($restaurants);
    }

    public function registerCustomer(Request $request){
        $request->validate([
            'name' => 'required',
            'emailAddress' => 'required',
            'contactNumber' => 'required',
            'password' => 'required|same:confirmPassword',
            'confirmPassword' => 'required',
        ]);
        
        $customer = CustomerAccount::create([
            'name' => $request->name,
            'emailAddress' => $request->emailAddress,
            'contactNumber' => $request->contactNumber,
            'password' => Hash::make($request->password),
            'profileImage' => "",
        ]);
        return response()->json($customer);
    }

    public function updateCustomer(Request $request){

    }
}
