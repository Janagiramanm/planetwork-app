<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Holiday;
use App\Models\TrackLocations;
use App\Models\WorkReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class Attendances extends Component
{
   
    public $months, $years, $sub, $monthInDays = [];
    public $result,$month,$year,$attendance, $details, $employee,$date_display,$from_address,$to_address,
           $monthDays, $month_no, $total_days, $total_hours, $date, $reslatLong, $ideal_locations,
           $distance, $user_name;
    public $detailView, $attendanceView, $mapView, $mapPath =false;

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
        $this->attendanceView = false;

        $month_no = date('m',strtotime('01-'.$this->month.'-'.$this->year));
        $this->monthDays = Carbon::now()->month($month_no)->daysInMonth;
       
        $date_val = $this->year.'-'.$month_no;
        $this->employee = User::find($user_id);
        $this->user_id = $user_id;
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
        // $this->totalHours = $this->getTotalWorkingHoursMonth();
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

    public function pathView($user_id, $dateVal){

        $this->mapView = true;
        $this->mapPath = false;
        $this->detailView = false;
        $this->attendanceView = false;
        $this->dateVal = date('Y-m-d',strtotime($dateVal));
        $this->userId = $user_id;
        // $this->dateVal - $dateVal;
        $this->report =  WorkReport::where('user_id','=', $this->userId)
        ->where('created_at','LIKE',$this->dateVal.'%')->get();

       if($this->report){
           foreach($this->report as $key => $value){
               $this->user_name = $value->user->name;
               $this->date_display = date('d-m-Y',strtotime($value->created_at));
               $this->distance = $value->travel_distance;
               $this->from_address = $value->from_address;
               $this->to_address = $value->to_address;
           }
       }
       $this->apiKey = env('GOOGLEMAPAPI');

    }

    public function getDetailMapData(){
        $res = [];
        $reslatLong=[];
        $this->mapView = true;
        $this->mapPath = true;
       

        
        $this->apiKey = env('GOOGLEMAPAPI');
        // $idealLocation = $this->idealLocations($this->uId, $this->date);
        $this->locations = TrackLocations::where('date', '=', $this->dateVal)
        ->where('user_id', '=', $this->userId)
        // ->whereBetween('time',[$start_time,$end_time])
        ->orderBy('time', 'asc')
        ->get();

        // echo '<pre>';
        // print_r($this->locations);

        if(!$this->locations ->isEmpty()){
            foreach($this->locations as $key => $value){
                $details = '<b>'.$value->user->name.'</b><br> Date : '.date('d-m-Y',strtotime($value->date)) 
                          .'<br> Time : '. $value->time;
                $reslatLong[] = ['lat'=>$value->latitude, 'lng'=>$value->longitude, 'time'=>$value->time];
            }
            $this->reslatLong =  json_encode($reslatLong, JSON_NUMERIC_CHECK);
        }
       
    }

    // public function getNumberOfDays(){

    //     $month_no = array_keys($this->months,$this->month);
    //     $no = ($month_no[0]+1 < 10 ) ? '0'.$month_no[0]+1 : $month_no[0]+1 ;
    //     $this->monthDays = Carbon::now()->month($no)->daysInMonth;
    // }
}
