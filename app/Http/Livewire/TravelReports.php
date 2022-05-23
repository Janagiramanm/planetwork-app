<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserRole;
use App\Models\WorkReport;

class TravelReports extends Component
{
    public $month, $year, $user_id, $month_number, $travelResult, $title;
    public $result, $months, $years, $details,  $employee = [];
    public $travelReport, $travelDetail = false;
    public function render()
    {
        $now = Carbon::now();
        $this->travelReport = true;
        $this->month =$this->month? $this->month : date('M');
        $this->year = $now->year;
        for($i=1; $i <=3; $i++ ){
             $this->years[] = $this->year+1 - $i;
        }
        $this->users = UserRole::where('role_id','=',3)->get();
        $this->months = ['January','February','March','April','May',
        'June','July','August','September','October','November','December'];

        $this->month_number = date("m",strtotime($this->month));
        $date = $this->year.'-'.$this->month_number;
        $this->result = WorkReport::when($this->user_id, function ($query, $user_id) {
                  return $query->where('user_id', '=', $user_id);
         })->groupBy('user_id')
        ->selectRaw('user_id, sum(travel_distance) as travel_distance')
        ->where('date','LIKE',$date.'-%')->get();

        return view('livewire.reports.travel-reports');
    }

    public function backToReports(){

        $this->travelDetail = false;
        return $this->redirect('/travelReport');
    }

    public function viewTravelDetails($user_id){
        $this->travelDetail = true;
        $date = $this->year.'-'.$this->month_number;
        $this->employee = User::find($user_id);
        $this->travelResult = WorkReport::select('work_reports.*')->join('jobs', 'work_reports.job_id', '=', 'jobs.id')
                  ->join('customers','jobs.customer_id', '=','customers.id')
                  ->when($this->title, function ($query, $title) {
                              return $query->where('jobs.sr_no', '=', $title)
                              ->orwhere('customers.first_name','like',$title.'%')
                              ->orwhere('customers.company_name','like',$title.'%');
                  })
                  ->where('work_reports.user_id','=', $user_id)
                  ->where('work_reports.date','LIKE',$date.'-%')
                  ->where('work_reports.job_id','!=','0')
                  ->get();
        // echo '<pre>';
        // print_r($this->travelResult);
    }

    
}
