<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class Attendances extends Component
{
   
    public $months, $years, $sub = [];
    public $result,$month,$year,$attendance, $details, $employee;
    public $detailView, $attendanceView =false;

    public function render()
    {
        $this->attendanceView = true;
        $this->months = ['January','February','March','April','May',
        'June','July','August','September','October','November','December'];
        $now = Carbon::now();
        $this->month =$this->month? $this->month : date('M');
        $this->year = $now->year;
        for($i=1; $i <=3; $i++ ){
             $this->years[] = $this->year+1 - $i;
        }
        $month_number = date("m",strtotime($this->month));
        $date = $this->year.'-'.$month_number;

        $this->result = Attendance::groupBy('user_id')
        ->selectRaw('user_id,sum(minutes) as minutes')
        ->where('date','LIKE',$date.'-%')->get();
      
        return view('livewire.attendance.attendance');
    }


    public function backPage(){
          return $this->redirect('/attendance');
    }

    public function detailView($user_id){

        $this->detailView = true;

        $month_number = date("m",strtotime($this->month));
        $date = $this->year.'-'.$month_number;
        $this->employee = User::find($user_id);

        // $this->details = Attendance::groupBy('user_id','date')
        // ->selectRaw('user_id,sum(minutes) as minutes, date')
        // ->where('date','LIKE',$date.'-%')->get();

        // $this->details = Attendance::where('date','LIKE',$date.'-%')
        // ->where('user_id','=',$user_id)
        // ->get();

        // $this->details = Attendance::select(DB::raw('date','min(login) as login', 'max(logout) as logout', 'sum(minutes) as minutes'))
        //  ->where('user_id','=',$user_id)
        //  ->groupBy('date')
        //  ->get();

         $this->details =  DB::select("select user_id,date,min(login) as login,max(logout) as logout,sum(minutes) as minutes FROM `attendances` GROUP BY date,user_id");

      

        //  $sub = Attendance::select('date', DB::raw('MAX(login) as start_date'))
        //  ->where('user_id','=',$user_id)
        //  ->groupBy('date');

        // $this->details =  Attendance::join(DB::raw("($sub->toSql) max_table", function($join){
        //     $join->on('max_table.date','=', 'synopses.date')
        //     ->on('max_table.start_date', '=', 'synopses.login');
        // }))
        // ->addBindings($sub->getBindings(),'join');
       
    }
}
