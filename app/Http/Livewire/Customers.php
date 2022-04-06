<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\City;
use App\Models\CustomerLocation;
use Livewire\Component;
use Livewire\WithPagination;



class Customers extends Component
{
    public $customer, $confirmingItemDeletion, $customer_id, $error, $address_edit;
    public $updateMode,$createMode, $addMore, $editLocationMode, $addnewBranch = false;
    public $show = true;
   
    public $first_name,$last_name, $customer_type,
     $customer_email, $company_name, $phone, $website , $branch, $city, $address,  $latitude, $longitude ;
   
    public $edit_branch,$edit_city,$edit_lat,$edit_lng, $edit_address, $edit_location_id;
    public $locations = [];
   
    protected $listeners = [
        'customerGetLatLngForInput','customerLatLngChange'
   ];
  
    public $i=0;

    use WithPagination;

    public function render()
    {
       // $this->customers = Customer::paginate(10);
       $this->cities = City::all();

        return view('livewire.customer.list', [
            'customers' => Customer::paginate(5),
        ]);
      
    }

    private function resetInput()
    {
        $this->first_name = null;
        $this->last_name = null;
        $this->company_name = null;
        $this->customer_email = null;
        $this->branch = null;
        $this->error = false;
        $this->locations = [];
        $this->resetValidation();
        $this->render();
    }

    public function create(){
        $this->createMode = true;
        $this->addMore = true;
        $this->show = true;
        $this->error = false;
        return view('livewire.customer.create');
    }

    public function store()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'customer_email' => 'required',
            'customer_type' => 'required',
            'company_name' => 'required_if:customer_type,==,BUSINESS',
            'branch.0' => 'required',
            'city.0' => 'required',
            // 'address.0' => 'required'

        ],
        // [
        //     'branch.*.required' => 'branch field is required',
        //     'city.*.required' => 'city field is required',row
        //     'address.*.required' => 'address field is required.',
        // ]
    );


       $customer_id = Customer::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company_name' => $this->company_name,
            'customer_type' => $this->customer_type,
            'customer_email' => $this->customer_email,
            'phone' => $this->phone,
            'website' => $this->website,
        ])->id;

        foreach ($this->branch as $key => $value) {
            
            CustomerLocation::create([
                   'customer_id' => $customer_id,
                   'branch' => $this->branch[$key], 
                   'city_id' => $this->city[$key],
                   'address' => $this->address[$key],
                   'latitude' => $this->latitude[$key],
                   'longitude' => $this->longitude[$key]
                ]);
        }

        $this->createMode = false;
        $this->resetInput();
        
    }

    public function edit($id)
    {
        $this->updateMode = true;
        // $this->addMore =false;
        $this->customer_id = $id;
        $customer = Customer::where('id',$this->customer_id)->first();
        $this->customer_type = $customer->customer_type;
        $this->first_name = $customer->first_name;
        $this->last_name = $customer->last_name;
        $this->customer_email = $customer->customer_email;
        $this->company_name = $customer->company_name;
        $this->phone = $customer->phone;
        $this->website = $customer->website;
        $this->locations = CustomerLocation::where('customer_id','=', $this->customer_id)->get();
        $this->edit_branch =null;     
        $this->edit_city =null;     
        $this->edit_lat =null;     
        $this->edit_lng =null; 
        $this->resetValidation();    
    }


    public function destroy($id)
    {
        if ($id) {
            $record = Customer::where('id', $id);
            $record->delete();
        }
    }

    public function view(){
        $this->createMode = false;
        $this->updateMode = false;
        $this->editLocationMode = false;
        $this->addnewBranch = false;
        $this->resetInput();
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'customer_type' => 'required',
            'customer_email' => 'required|email',
            'company_name' => 'required_if:customer_type,==,BUSINESS'
        ]);

        if ($this->customer_id) {
            $customer = Customer::find($this->customer_id);
           
            $customer->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'company_name' => $this->company_name,
                'customer_type' => $this->customer_type,
                'customer_email' => $this->customer_email,
                'phone' => $this->phone,
                'website' => $this->website,
            ]);
            $this->updateMode = false;
            //session()->flash('message', 'Users Updated Successfully.');
            $this->resetInput();

        }
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->editLocationMode = false;
        $this->resetInput();


    }
    
    public function confirmItemDeletion( $id) 
    {
        $this->confirmingItemDeletion = $id;
    }
 
    public function deleteItem( Customer $customer) 
    {
        $customer->delete();
        $this->confirmingItemDeletion = false;
        session()->flash('message', 'Item Deleted Successfully');
    }



    public function add($i)
    {
        // $this->addMore = true;
        $i = $i + 1 ;
        $this->i = $i;
        array_push($this->locations ,$i);
    }

    public function remove($i)
    {
        unset($this->locations[$i]);
      
    }

    public function customerGetLatLngForInput($address, $latitude, $longitude, $row)
    {
        //   echo $address;exit;
            $this->address[$row] = $address;
            $this->latitude[$row] = $latitude;
            $this->longitude[$row] = $longitude;
    }
    public function customerLatLngChange($address, $latitude, $longitude)
    {
        //   echo $address;exit;
            $this->edit_address = $address;
            $this->edit_lat = $latitude;
            $this->edit_lng = $longitude;
    }

    public function editLocation($id){

        $this->customer_location_id = $id;
        $custLocation = CustomerLocation::find($id);
        $this->edit_branch = $custLocation->branch;
        $this->edit_city = $custLocation->city_id;
        $this->edit_lat = $custLocation->latitude;
        $this->edit_lng = $custLocation->longitude;
        $this->edit_address = $custLocation->address;
        $this->editLocationMode = true;
        $this->updateMode = false;

        
       // $this->edit($this->customer_id);
    }

    public function updateLocation(){

        $this->validate([
            'edit_branch' => 'required',
            'edit_city' => 'required',
            'edit_address' => 'required',
           
        ]);
        $location =  CustomerLocation::find($this->customer_location_id);
        $location->branch = $this->edit_branch;
        $location->city_id = $this->edit_city;
        $location->address = $this->edit_address;
        $location->latitude = $this->edit_lat;
        $location->longitude = $this->edit_lng;
        $location->save();
        $this->editLocationMode = false;
        $this->updateMode = true;
        $this->locations = CustomerLocation::where('customer_id','=', $this->customer_id)->get();
        $this->edit_branch =null;     
        $this->edit_city =null;     
        $this->edit_lat =null;     
        $this->edit_lng =null; 

    }


    public function addNewBranch($id){

          $this->addnewBranch = true;
          $this->updateMode = false;
    }

    public function saveNewBranch(){

        $this->validate([
            'edit_branch' => 'required',
            'edit_city' => 'required',
            'edit_address' => 'required',
           
        ]);
        $custLocation = new CustomerLocation();
        $custLocation->customer_id = $this->customer_id;
        $custLocation->branch = $this->edit_branch;
        $custLocation->city_id = $this->edit_city;
        $custLocation->address = $this->edit_address;
        $custLocation->latitude = $this->edit_lat;
        $custLocation->longitude = $this->edit_lng;
        $custLocation->save();
        $this->addnewBranch = false;
        $this->updateMode = true;
        $this->locations = CustomerLocation::where('customer_id','=', $this->customer_id)->get();
        $this->edit_branch =null;     
        $this->edit_city =null;     
        $this->edit_lat =null;     
        $this->edit_lng =null; 
    }

    protected $messages = [
        'edit_branch.required' => 'Please enter the branch name.',
        'edit_city.required' => 'Please select the city.',
        'edit_address.required' => 'Please enter the address.',
    ];
}
