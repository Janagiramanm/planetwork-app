<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class Attendances extends Component
{
   
    public $months, $years, $sub, $monthInDays = [];
    public $result,$month,$year,$attendance, $details, $employee,
           $monthDays, $month_no, $total_days, $total_hours;
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
        $month_no = array_keys($this->months,$this->month);
        $this->total_days = $month_number ;
        $this->total_hours = $this->total_days * 9;

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

        $month_no = date('m',strtotime('01-'.$this->month.'-'.$this->year));
        $this->monthDays = Carbon::now()->month($month_no)->daysInMonth;
       
        $date_val = $this->year.'-'.$month_no;
        $this->employee = User::find($user_id);
        for($i=1; $i <= $this->monthDays; $i++){
             $date = $i.'-'.$month_no.'-'.$this->year;
             $day = Carbon::createFromFormat('d-m-Y', $i.'-'.$month_no.'-'.$this->year)->format('l');
             $holiday = Holiday::where('date','=',date('Y-m-d',strtotime($date)))->first();
            
             $color = ($day == 'Sunday') ? "red" : (($holiday) ? "blue" : "white");
             $this->monthInDays[]  = [
                          'date'=> $date,
                          'day'=> $day,
                          'color' => $color,
                          'holiday' => $holiday ? $holiday->description : '' 
            ];
           
        }
        // echo "select user_id,date,min(login) as login,max(logout) as logout,sum(minutes) as minutes 
        // FROM `attendances` where user_id = $user_id and date LIKE '$date_val%' GROUP BY date,user_id";

        $this->details =  DB::select("select user_id,date,min(login) as login,max(logout) as logout,sum(minutes) as minutes 
                              FROM `attendances` where user_id = $user_id and date LIKE '$date_val%' GROUP BY date,user_id");

    }

    public function getTotalWorkingHoursMonth(){
        $month_number = date("m",strtotime($this->month));
        $holiday = Holiday::where('date','=',date('Y-m-d',strtotime($date)))->first();
        $totalDays = Carbon::now()->month($month_number)->daysInMonth;

    }
    public function getNumberOfDays(){

        $month_no = array_keys($this->months,$this->month);
        $no = ($month_no[0]+1 < 10 ) ? '0'.$month_no[0]+1 : $month_no[0]+1 ;
        $this->monthDays = Carbon::now()->month($no)->daysInMonth;
    }
}
