<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Leave;
use App\Models\Api\LeaveDetail;
use Carbon\Carbon;
use App\Models\EmployeeDetail;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       
        echo "comes";exit;

    }


    public function create(Request $request){
        echo "create";exit;
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

    public function apply(Request $request){

        $user_id = $request->user_id;
        $user = Leave::where('user_id','=', $user_id)->first();
        if(!$user){ 
                $user = new Leave();
                $user->user_id = $request->user_id;
                $user->save();
        }

        $start = Carbon::parse($request->from_date);
        $end =  Carbon::parse($request->to_date);
        $no_of_days = $end->diffInDays($start) + 1;

        $available_leave = $user->earned_leave - $user->leave_taken;
         
         $leave_detail = new LeaveDetail();
         $leave_detail->from_date = $request->from_date;
         $leave_detail->to_date = $request->to_date;
         $leave_detail->user_id = $user_id;
         $leave_detail->reason = $request->reason;
         $leave_detail->leave_type = $request->leave_type;
         $leave_detail->request_days = $no_of_days;
         $leave_detail->available_days = $available_leave;
         $leave_detail->save();

         return response()->json( [
            'status' => 1,
            'message' => 'successfully applied',
           
        ],200);
    }

    public function leaves(Request $request){
        
        $user_id = $request->user_id;
        $leaves = Leave::where('user_id','=',$user_id)->first();
        if(!$leaves){
            return response()->json( [
                'status' => 0,
                'message' => 'Data not found',
               
            ],200);
        }
        
        return response()->json( [
            'status' => 1,
            'leaves' => $leaves,
           
        ],200);
    }

    public function leaveHistory(Request $request){
        $user_id = $request->user_id;
        $result = [];
        $leave = Leave::where('user_id','=', $user_id)->get();
        $leaveHistory = LeaveDetail::where('user_id','=',$user_id)->get();
        if($leave->isEmpty()){
            return response()->json( [
                'status' => 0,
                'message' => 'Data not found',
               
            ],200);
        }

        foreach($leave as $key => $val){
            $leaves['earned_leave'] = $val->earned_leave;          
        }

        
 
         if($leaveHistory){
            foreach($leaveHistory as $key => $value){
                
                    $start = Carbon::parse($value->from_date);
                    $end =  Carbon::parse($value->to_date);
                    $days = $end->diffInDays($start);

                    $result[] = [
                            'from_date' => $value->from_date,
                            'to_date' => $value->to_date,
                            'leave_type' => $value->leave_type,
                            'reason' => $value->reason,
                            'status' => $value->status,
                            'no_of_days' => $days + 1
                    ];
                
            }
        }
        return response()->json( [
            'status' => 1,
            'leaves' => $leaves,
            'history' => $result,
            
        ],200);
    }

    public function updateEarnedLeave(){

        $employees = EmployeeDetail::all();

        $current_date = date('Y-m-d');
     //   print_r($employees);
        if(!$employees){
            return [
                'status'=> 0 ,
                'message' => "No data found"
            ];
        }
        
        foreach($employees as $employee){
                $employee->date_of_join;
                $join_date = Carbon::parse($employee->date_of_join);
                $cur_date =  Carbon::parse($current_date);
                $employee->date_of_join ."--".$current_date;
                $months = $cur_date->diffInMonths($join_date);
                $earnedLeave = $months * 1.5;
                $leaves = Leave::where('user_id','=', $employee->user_id)->first();
                if($leaves){
                    $leave = Leave::find($leaves->id);
                    $leave->earned_leave = $earnedLeave;
                    $leave->save();
                }
                
        }
        return[
            'status' => 1,
            'message' => 'successfully updated'
        ];
    }

}
