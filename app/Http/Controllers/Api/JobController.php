<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Task;
use App\Models\Customer;
use App\Models\AssignJobEmployee;
use App\Models\OvertimeJob;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
       
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getJobs(Request $request){

        $user_id = $request->user_id;

        $jobs = AssignJobEmployee::where('user_id','=', $user_id)->get();
        if($jobs->isEmpty()){
            return response()->json([
                'status' => 0,
                'message' => 'No data found'
    
            ]);
        }
      
        foreach($jobs as $key => $value){
            
               $customer_name = $value->job->customer->company_name;
               if($value->job->customer->customer_type == 'INDIVIDUAL'){
                 $customer_name =   $value->job->customer->first_name.' '.$value->job->customer->last_name;
               }
        
                $result[] = [
                    'sr_no' => ($value->job->id < 100) ? 'SR00'.$value->job->id : 'SR'.$value->job->id,
                    'job_id' => $value->job->id,
                    'job_assign_id' => $value->id,
                    'job_date' => $value->job->date,
                    'customer_name' => $customer_name,
                    'branch' => $value->job->customerLocation->branch,
                    'address' => $value->job->customerLocation->address,
                    'latitude' => $value->job->customerLocation->latitude,
                    'longitude' => $value->job->customerLocation->longitude,
                    'city' => $value->job->customerLocation->city->name,
                    'status' => $value->job_status,
                    'no_of_visit' =>  $value->no_of_visit
                ];
        }
        
        return response()->json([
            'status' => 1,
            'jobs' => $result

        ]);

    }

    public function jobUpdate(Request $request){

        $job_id = $request->job_id;
        $user_id = $request->user_id;
        $job_status = $request->job_status;
        $assign_id = $request->job_assign_id;

        // $job = Job::find($request->job_id);
        // $current_date = date('Y-m-d');
        // $job->status = $job_status;
        // $job->save();

        $jobUpdate = AssignJobEmployee::find($assign_id);
        if(!$jobUpdate){
            return response()->json([
                'status' => 0,
                'message' => 'No data found'
    
            ]);
        }

        $jobUpdate->job_status = $job_status;
        $jobUpdate->no_of_visit = $request->work_visit;
        $jobUpdate->save();

       

        return response()->json([
            'status' => 1,
            'message' => 'Successfully Updated'

        ]);

    }

    public function tasks(Request $request){

          $tasks = Task::all();
          if(!$tasks){
              return[
                  'status' => 0,
                  'message' => 'Not data found'
              ];
          }

          return[
                 'status' => 1,
                 'data' => $tasks
          ];

    }

    public function customers(Request $request){

         $customer_type = $request->customer_type;
         if($customer_type){
              $customers = Customer::with('customerLocation')->where('customer_type','=', $customer_type)->get();
              if($customers->isEmpty()){
                  return [
                      'status' => 0,
                      'message' => 'Data not found'
                  ];
              }

              return [
                  'status' => 1,
                  'data' => $customers
              ];

         }
    }

    public function addOverTime(Request $request){

          $overTime = new OvertimeJob();
          $overTime->user_id = $request->user_id;
          $overTime->customer_id = $request->customer_id;
          $overTime->task_id = $request->task_id;
          $overTime->date = $request->date;
          $overTime->start_time = $request->start_time;
          $overTime->end_time = $request->end_time;
          $overTime->description = $request->description;
          if($overTime->save()){
              return [
                  'status' => 1, 
                  'message' => 'Overtime Created Successfully.'
              ];
          }

          return [
               'status' => 0 ,
               'message' => 'Oops Something went wrong.'
          ];
    }



}
