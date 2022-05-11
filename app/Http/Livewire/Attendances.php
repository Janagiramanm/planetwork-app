<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use Carbon\Carbon;

class Attendances extends Component
{
   
    public $months, $years = [];
    public $result,$month,$year,$attendance;
    public function render()
    {
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

        // $this->result = Attendance::where('date','LIKE',$date.'-%')->get();

        // echo '<pre>';
        // print_r($this->result);
        // exit;



        $this->result = Attendance::groupBy('user_id','date')
        ->selectRaw('user_id,date(date),sum(minutes) as minutes')
        ->where('date','LIKE',$date.'-%')->get();
       

        return view('livewire.attendance.attendance');
    }
    public function backPage(){

        
        return $this->redirect('/attendance');
    }
}
