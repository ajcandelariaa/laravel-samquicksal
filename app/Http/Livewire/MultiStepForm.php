<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\RestaurantApplicant;
use App\Mail\RestaurantFormAppreciation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class MultiStepForm extends Component
{
    use WithFileUploads;

    public $acceptLicense;
    public $fname, $mname, $lname, $address, $city, $postalCode, $state, $country, $role, $birthDate, $gender, $contactNumber, $landlineNumber, $emailAddress;
    public $rName, $rBranch, $rAddress, $rCity, $rPostalCode, $rState, $rCountry;
    public $bir, $dti, $mayorsPermit, $staffValidId;
    public $totalSteps = 5;
    public $currentStep = 1;

    public function render(){
        return view('livewire.multi-step-form');
    }

    public function mount(){
        $this->currentStep = 1;
    }

    public function increaseStep(){
        $this->resetErrorBag();
        $this->validateData();
        $this->currentStep++;
        if($this->currentStep > $this->totalSteps){
            $this->currentStep = $this->totalSteps;
        }
    }

    public function decreaseStep(){
        $this->resetErrorBag();
        $this->currentStep--;
        if($this->currentStep < 1){
            $this->currentStep = 1;
        }
    }

    public function validateData(){
        if ($this->currentStep == 1){
            $this->validate([
                'acceptLicense' => 'accepted'
            ]);
        } else if($this->currentStep == 2){
            $todayDate = date('Y/m/d');
            
            $this->validate([
                'fname' => 'required|regex:/^[\pL\s\-]+$/u',
                'mname' => 'nullable|regex:/^[\pL\s\-]+$/u',
                'lname' => 'required|regex:/^[\pL\s\-]+$/u',
                'role' => 'required',
                'birthDate' => 'required',
                'gender' => 'required',
            ], 
            [
                'fname.required' => 'First Name is required',
                'fname.regex' => 'First Name must contain letters only',
                'mname.regex' => 'Middle Name must contain letters only',
                'lname.required' => 'Last Name is required',
                'lname.regex' => 'Last Name must contain letters only',
                'role.required' => 'Role is required',
                'birthDate.required' => 'Birthday is required',
                'gender.required' => 'Gender is required',
            ]);
        } else if($this->currentStep == 3){
            $this->validate([
                'address' => 'required',
                'city' => 'required',
                'postalCode' => 'required',
                'state' => 'required',
                'country' => 'required',
                'emailAddress' => 'required|email|unique:restaurant_accounts,emailAddress',
                'contactNumber' => 'required|digits:11',
                'landlineNumber' => 'digits:8',
            ], 
            [
                'address.required' => 'Address is required',
                'city.required' => 'City is required',
                'postalCode.required' => 'Postal/Zip Code is required',
                'state.required' => 'State/Province is required',
                'country.required' => 'Country is required',
                'emailAddress.required' => 'Email Address is required',
                'emailAddress.email' => 'Email Address must be a valid email',
                'emailAddress.unique' => 'Email Address has already taken',
                'contactNumber.required' => 'Contact Number is required',
                'contactNumber.digits' => 'Contact Number must be 11 digits',
                'landlineNumber.digits' => 'Landline Number must be 8 digits',
            ]);
        } else if ($this->currentStep == 4){
            $this->validate([
                'rName' => 'required',
                'rBranch' => 'required',
                'rAddress' => 'required',
                'rCity' => 'required',
                'rPostalCode' => 'required',
                'rState' => 'required',
                'rCountry' => 'required'
            ], 
            [
                'rName.required' => 'Name is required',
                'rBranch.required' => 'Branch is required',
                'rAddress.required' => 'Address is required',
                'rCity.required' => 'City is required',
                'rPostalCode.required' => 'Postal/Zip Code is required',
                'rState.required' => 'State/Province is required',
                'rCountry.required' => 'Country is required',
            ]);
        }
    }

    public function register(){
        $this->resetErrorBag();
        if($this->currentStep == 5){
            $validatedData = $this->validate([
                'bir' => 'required|mimes:pdf',
                'dti' => 'required|mimes:pdf',
                'mayorsPermit' => 'required|mimes:pdf',
                'staffValidId' => 'required|mimes:pdf',
            ], 
            [
                'bir.required' => 'BIR Certificate of Registration is required',
                'bir.mimes' => 'BIR Certificate of Registration must be in pdf format',

                'dti.required' => 'DTI Business Registration is required',
                'dti.mimes' => 'DTI Business Registration must be in pdf format',

                'mayorsPermit.required' => 'Mayor’s Permit is required',
                'mayorsPermit.mimes' => 'Mayor’s Permit must be in pdf format',

                'staffValidId.required' => 'Owner/Staff Valid ID is required',
                'staffValidId.mimes' => 'Owner/Staff Valid ID must be in pdf format',
            ]);

            RestaurantApplicant::create([
                'status' => 'Pending',
                'fname' => $this->fname,
                'mname' => $this->mname,
                'lname' => $this->lname,
                'address' => $this->address,
                'city' => $this->city,
                'postalCode' => $this->postalCode,
                'state' => $this->state,
                'country' => $this->country,
                'role' => $this->role,
                'birthDate' => $this->birthDate,
                'gender' => $this->gender,
                'contactNumber' => $this->contactNumber,
                'landlineNumber' => $this->landlineNumber,
                'emailAddress' => $this->emailAddress,

                'rName' => $this->rName,
                'rBranch' => $this->rBranch,
                'rAddress' => $this->rAddress,
                'rCity' => $this->rCity,
                'rPostalCode' => $this->rPostalCode,
                'rState' => $this->rState,
                'rCountry' => $this->rCountry,

                'bir' => "",
                'dti' => "",
                'mayorsPermit' => "",
                'staffValidId' => "",
            ]);
            
            $resultId = DB::table('restaurant_applicants')->get('id')->last();
            $nextIdFolderName = $resultId->id;

            $birName = 'BIR_'.str_replace(' ', '', $validatedData['bir']->getClientOriginalName());
            $dtiName = 'DTI_'.str_replace(' ', '', $validatedData['dti']->getClientOriginalName());
            $mayorsPermitName = 'MayorsPermit_'.str_replace(' ', '', $validatedData['mayorsPermit']->getClientOriginalName());
            $staffValidIdName = 'ValidId_'.str_replace(' ', '', $validatedData['staffValidId']->getClientOriginalName());
            
            $this->bir->storeAs('uploads/restaurantApplicants/'.$nextIdFolderName, $birName, 'public');
            $this->dti->storeAs('uploads/restaurantApplicants/'.$nextIdFolderName, $dtiName, 'public');
            $this->mayorsPermit->storeAs('uploads/restaurantApplicants/'.$nextIdFolderName, $mayorsPermitName, 'public');
            $this->staffValidId->storeAs('uploads/restaurantApplicants/'.$nextIdFolderName, $staffValidIdName, 'public');
            
            RestaurantApplicant::where('id', $nextIdFolderName)
                ->update([
                'bir' => $birName,
                'dti' => $dtiName,
                'mayorsPermit' => $mayorsPermitName,
                'staffValidId' => $staffValidIdName
            ]);

            $details = [
                'applicantName' => $this->fname.' '.$this->lname
            ];

            Mail::to($this->emailAddress)->send(new RestaurantFormAppreciation($details));
            Session::flash('registered');
            return redirect('/restaurant/register');
        }
    }
}
