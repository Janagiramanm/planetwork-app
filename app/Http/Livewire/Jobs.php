<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Job;
use App\Models\User;
use App\Models\Task;
use App\Models\Role;
use App\Models\Customer;
use App\Models\CustomerLocation;
use App\Models\AssignJobEmployee;
use Illuminate\Support\Facades\DB;

class Jobs extends Component
{
    public $jobs, $customer_type, $business, $individual, $customer_id, $tasks, $task_id, $branch, $address;
    public $updateMode,$createMode, $showLocation = false;
    public  $name, $confirmingItemDeletion, $locations, $customers, $user_id, $date;
    public $selCity = '';
    public $show = true;
    public $state = [
		'status' => 'SCHEDULED',
        'order_position' => 0,
	];

    public function render()
    {
        $this->jobs = Job::all();
        $this->current_date = date('Y-m-d');

        return view('livewire.jobs.list');
    }

    public function create(){
        $this->createMode = true;
        $this->show = true;
        $this->customer_type = 'BUSINESS';
        //$this->customers = Customer::where('customer_type','=',$this->customer_type)->get();
        $this->business = Customer::where('customer_type','=','BUSINESS')->get();
        $this->individual = Customer::where('customer_type','=','INDIVIDUAL')->get();
        $this->employees = User::join('user_roles', 'user_roles.user_id', '=', 'users.id')
                                ->join('roles', 'user_roles.role_id', '=', 'roles.id')
                                ->where('roles.name','=','employee')
                                ->get(['users.*']);
        
        
        $this->tasks = Task::all();
        return view('livewire.jobs.create');
    }

    public function loadcustomers(){
        $this->customers = Customer::where('customer_type','=',$this->customer_type)->get();
        
    }

    public function address(){
        $this->showLocation = true;
        $this->locations = CustomerLocation::where('customer_id','=', $this->customer_id)->get();
       // print_r($this->locations);
       
    }

    public function store(){

        $this->validate([
            'customer_type' => 'required',
            'customer_id' => 'required',
            'address' => 'required',
            'task_id' => 'required',
            'user_id' => 'required',
            'date' => 'required'
        ]);

        $job_id = Job::create([
            'customer_id' => $this->customer_id,
            'address' => $this->address,
            'task_id' => $this->task_id,
            'date' => $this->date           
        ])->id;

        foreach($this->user_id as $key => $user_id)
        AssignJobEmployee::create([
                 'job_id' => $job_id,
                 'user_id' => $user_id,
                 
        ]);

        $this->createMode = false;
        $this->resetInput();
    }

    public function edit($id){
        $this->updateMode = true;
        $this->showLocation = true;
        $this->job_id = $id;
      
        $this->employees = User::join('user_roles', 'user_roles.user_id', '=', 'users.id')
        ->join('roles', 'user_roles.role_id', '=', 'roles.id')
        ->where('roles.name','=','employee')
        ->get(['users.*']);

        $job = Job::where('id', $id)->first();
        $this->customer_id = $job->customer_id;
        $this->locations = CustomerLocation::where('customer_id','=', $this->customer_id)->get();
        $this->business = Customer::where('customer_type','=','BUSINESS')->get();
        $this->individual = Customer::where('customer_type','=','INDIVIDUAL')->get();
        $this->tasks = Task::all();
        $this->customer_type = $job->customer->customer_type;
        $this->task_id = $job->task_id;
        $this->user_id = explode(',',$job->employees); 
        $this->date = $job->date;
        $this->address = $job->address;
    }


    public function update(){

        $this->error = true;

        $this->validate([
            'customer_type' => 'required',
            'customer_id' => 'required',
            'address' => 'required',
            'task_id' => 'required',
            'user_id' => 'required',
            'date' => 'required'
        ]);


        if ($this->job_id) {
            $job = Job::find($this->job_id);
           
            $job->update([
                'customer_id' => $this->customer_id,
                'address' => $this->address,
                'task_id' => $this->task_id,
                'date' => $this->date    
            ]);

            AssignJobEmployee::where('job_id','=',$this->job_id)->delete();
            foreach($this->user_id as $key => $user_id)
            AssignJobEmployee::create([
                     'job_id' => $this->job_id,
                     'user_id' => $user_id,
            ]);

            $this->updateMode = false;
            //session()->flash('message', 'Users Updated Successfully.');
            $this->resetInput();

        }
        $this->createMode = false;
        $this->resetInput();
    }


    public function view(){
        $this->createMode = false;
        $this->updateMode = false;
        $this->resetInput();
    }
    private function resetInput()
    {
        $this->customer_type = null;
        $this->customer_id = null;      
        $this->address = null;      
        $this->render();
    }
}
