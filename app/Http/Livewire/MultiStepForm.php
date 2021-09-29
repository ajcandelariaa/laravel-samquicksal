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
            $this->validate([
                'fname' => 'required',
                'lname' => 'required',
                'role' => 'required',
                'birthDate' => 'required',
                'gender' => 'required',
            ]);
        } else if($this->currentStep == 3){
            $this->validate([
                'address' => 'required',
                'city' => 'required',
                'postalCode' => 'required',
                'state' => 'required',
                'country' => 'required',
                'contactNumber' => 'required',
                'emailAddress' => 'required'
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
            ]);
        }
    }

    public function register(){
        $this->resetErrorBag();
        if($this->currentStep == 5){
            $validatedData = $this->validate([
                'bir' => 'required',
                'dti' => 'required',
                'mayorsPermit' => 'required',
                'staffValidId' => 'required',
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
            Session::flash('success', "Your form has been submitted, kindly check your email for further instructions");
            return redirect('/restaurant/register');
        }
    }
}
