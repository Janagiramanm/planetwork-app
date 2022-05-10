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
use Carbon\Carbon;
use Illuminate\Http\Request;

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
     
        $job_id = isset($_GET['id'])?$_GET['id']:'';

        // $this->jobs = Job::all();
        $job = new Job();
        $this->jobs =  $job::when($job_id, function($job) use($job_id){
            if($job_id!=''){
              return $job->where('id',$job_id);
            }
          })->get();
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
        $this->date = Carbon::now()->format('Y-m-d');
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

        $job = Job::find($job_id);
        $job->sr_no = 'SR00'.$job_id;
        $job->save();

        foreach($this->user_id as $key => $user_id){
            AssignJobEmployee::create([
                    'job_id' => $job_id,
                    'user_id' => $user_id,
                    
            ]);

            $task = Task::find($this->task_id);
            $task_name = $task->name;
            $user = User::find($user_id);
            $this->sendFCM($task_name, $user->fcm_token);

        }

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

    function sendFCM($message, $id) {


        $url = 'https://fcm.googleapis.com/fcm/send';
    
        $fields = array (
                'registration_ids' => array (
                        $id
                ),
                'notification' => array (
                        "message" => $message,
                        "body" => $message.' task has been assigned to you. Please check',
                        "tittle" => 'New Task Assigned',
    
                )
        );
        $fields = json_encode ( $fields );
    
        $headers = array (
                'Authorization: key=' . "AAAA2Wnyh5E:APA91bEx41Qa5J1GrOxFxCMpKB55KqTkVDsJifETp3wAgnb2Kw3OOcEYExda59aovjuNMrEH9FF8riRb0wYp4lfAXxrhaxia6XnBFPtWdrz9FQUr1_pSztCrZ6779uz3r1HvxDkFngjw",
                'Content-Type: application/json'
        );
    
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    
        $result = curl_exec ( $ch );
        curl_close ( $ch );
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
