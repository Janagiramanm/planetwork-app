<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\UserRole;
use App\Models\TrackLocations;
use App\Models\AssignJobEmployee;
use App\Models\WorkReport;
use Carbon\Carbon;
use DB;
use DateTime;

class Reports extends Component
{
    public $user_id, $from_date,$to_date, $now, $current_month, $current_year, $month, $year, $title;
    public $result, $months, $years = [];
    public $reportShow, $processing, $detailReport, $viewPath, $reportView = false;
    public $status = ['logout','login','pause'];
    // public $months = ['01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May',
    //                   '06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'];
    public function render()
    {

       
        $now = Carbon::now();
        $this->month =$this->month? $this->month : date('M');
        $this->year = $now->year;
        for($i=1; $i <=3; $i++ ){
             $this->years[] = $this->year+1 - $i;
        }
        $this->users = UserRole::where('role_id','=',3)->get();
        $this->months = ['January','February','March','April','May',
        'June','July','August','September','October','November','December'];

        return view('livewire.reports.reports');
    }

    // public function generateWorkReport(){
    //             $this->validate([
    //                 'user_id' => 'required',
    //                 'from_date' => 'required',
    //                 'to_date' => 'required',
    //             ]);
    //             $this->show =true;
    //             $this->detailReport = false;
    //             $this->result = null;

    //             $jobs = AssignJobEmployee::where('user_id','=', $this->user_id)
    //                                       ->whereBetween('created_at',[$this->from_date, $this->to_date])->get();

    //             echo "<pre>";
    //             //print_r($jobs);
    //             if($jobs){
    //                 foreach($jobs as $key => $value){
    //                     // $dateWiseData[$value->date] =  DB::select('(SELECT * FROM track_locations where user_id = '.$this->user_id.' and date = "'.$value->date.'" ORDER BY id LIMIT 1)
    //                     //                                       UNION ALL
    //                     //                                       (SELECT * FROM track_locations where user_id = '.$this->user_id.' and date = "'.$value->date.'" ORDER BY id DESC LIMIT 1)');
    //                     $this->result[$key]=[
    //                            'user_id' => $value['user_id'],
    //                            'user_name' => $value->user->name
    //                     ];
    //                     // $result['user_id'] = $value['user_id'];
    //                     // $result['user_name'] = $value->user->name;
    //                 }
    //                 print_r($this->result);
    //                 exit;
    //             }

    // }

   
    public function generateWorkReport(){
        $this->validate([
            'user_id' => 'required',
            'month' => 'required',
            'year' => 'required',
        ]);

        $this->reportShow =true;
        $this->reportView =true;
        $this->detailReport = false;
        $this->result = null;


        $month_number = date("m",strtotime($this->month));

        $date = $this->year.'-'.$month_number;
        

        $res = WorkReport::where('user_id','=', $this->user_id)
                  ->where('date','LIKE',$date.'-%')
                  ->where('job_id','!=','0')
                  ->get();

        // $res = WorkReport::join('jobs', 'work_reports.job_id', '=', 'jobs.id')
        //           ->join('customers','jobs.customer_id', '=','customers.id')
        
        //           ->when($this->title, function ($query, $title) {
                            
        //                       return $query->where('jobs.sr_no', '=', $title)
        //                       ->orwhere('customers.first_name','like',$title.'%')
        //                       ->orwhere('customers.company_name','like',$title.'%');
        //           })
        //           ->where('work_reports.user_id','=', $this->user_id)
        //           ->where('work_reports.date','LIKE',$date.'-%')
        //           ->where('work_reports.job_id','!=','0')
        //           ->get();
       
        
        $dateWiseData = [];
        if($res){
            echo '<pre>';
            print_r($res); 
            exit;

            foreach($res as $key => $value){
                $dateWiseData[$key]['date'] = date('d M Y',strtotime($value['created_at']));
                
                // $dateWiseData[$key]['date'] = Carbon::createFromFormat('d/M/Y', $value->date);
                
                $dateWiseData[$key]['customer_name'] = '';
                $dateWiseData[$key]['job_name'] = '';
                $dateWiseData[$key]['job_status'] = '';
                $dateWiseData[$key]['sr_no'] = '';
                $dateWiseData[$key]['from_address'] = '';
                $dateWiseData[$key]['to_address'] = '';
                $dateWiseData[$key]['start'] = '';
                $dateWiseData[$key]['end'] = '';
                $dateWiseData[$key]['travel_distance'] = '';

                $dateWiseData[$key]['user_id'] = $value->user_id;
                $dateWiseData[$key]['user_name'] = $value->user->name;
                if($value->job_id != 0 ){
                    $dateWiseData[$key]['customer_name'] = $value->job->customer->first_name;
                    $dateWiseData[$key]['job_name'] = $value->job->task->name;
                    $dateWiseData[$key]['job_status'] = $value->job->employees;
                    $dateWiseData[$key]['sr_no'] = $value->job->sr_no;
                    $dateWiseData[$key]['job_id'] = $value->job_id;
                    $dateWiseData[$key]['customer_id'] = $value->job->customer_id;               
                    $dateWiseData[$key]['travel_distance'] = $value->travel_distance;
                    $dateWiseData[$key]['from_address'] = $value->from_address;
                    $dateWiseData[$key]['to_address'] = $value->job->customerLocation->address;
                    $dateWiseData[$key]['start'] = $value->start;
                    $dateWiseData[$key]['end'] = $value->end;
                }
                $dateWiseData[$key]['is_reached'] = $value->is_reached;
            }

        }
        $this->result = $dateWiseData;
    }

    public function getDateWiseData($user_id, $date, $job_id){
      return $result =  DB::select('(SELECT latitude,longitude,time FROM track_locations where user_id = '.$user_id.' and date = "'.$date.'" and job_id = '.$job_id.' ORDER BY id LIMIT 1)
                                UNION ALL
                             (SELECT latitude,longitude,time FROM track_locations where user_id = '.$user_id.' and date = "'.$date.'" and job_id = '.$job_id.' ORDER BY id DESC LIMIT 1)');
       

    }


    public function calculateDistanceBetweenTwoPoints($latitudeOne='', $longitudeOne='', $latitudeTwo='', $longitudeTwo='',$distanceUnit ='',$round=false,$decimalPoints='')
    {
        if (empty($decimalPoints)) 
        {
            $decimalPoints = '3';
        }
        if (empty($distanceUnit)) {
            $distanceUnit = 'KM';
        }

      
        $distanceUnit = strtolower($distanceUnit);
        $pointDifference = $longitudeOne - $longitudeTwo;
        $toSin = (sin(deg2rad($latitudeOne)) * sin(deg2rad($latitudeTwo))) + (cos(deg2rad($latitudeOne)) * cos(deg2rad($latitudeTwo)) * cos(deg2rad($pointDifference)));
        $toAcos = acos($toSin);
        $toRad2Deg = rad2deg($toAcos);

        $toMiles  =  $toRad2Deg * 60 * 1.1515;
        $toKilometers = $toMiles * 1.609344;
        $toNauticalMiles = $toMiles * 0.8684;
        $toMeters = $toKilometers * 1000;
        $toFeets = $toMiles * 5280;
        $toYards = $toFeets / 3;


              switch (strtoupper($distanceUnit)) 
              {
                  case 'ML'://miles
                         $toMiles  = ($round == true ? round($toMiles) : round($toMiles, $decimalPoints));
                         return $toMiles;
                      break;
                  case 'KM'://Kilometers
                       $toKilometers  = ($round == true ? round($toKilometers) : round($toKilometers, $decimalPoints));
                       return $toKilometers;
                      break;
                  case 'MT'://Meters
                        $toMeters  = ($round == true ? round($toMeters) : round($toMeters, $decimalPoints));
                        return $toMeters;
                      break;
                  case 'FT'://feets
                        $toFeets  = ($round == true ? round($toFeets) : round($toFeets, $decimalPoints));
                        return $toFeets;
                      break;
                  case 'YD'://yards
                        $toYards  = ($round == true ? round($toYards) : round($toYards, $decimalPoints));
                        return $toYards;
                      break;
                  case 'NM'://Nautical miles
                        $toNauticalMiles  = ($round == true ? round($toNauticalMiles) : round($toNauticalMiles, $decimalPoints));
                        return $toNauticalMiles;
                      break;
              }


    }

    public function getAddress($lat,$lng){
        $url="https://maps.google.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&key=".env('GOOGLEMAPAPI');
        $curl_return=$this->curl_get($url);
        $obj=json_decode($curl_return);
        return $obj->results[0]->formatted_address;
    }

    public function curl_get($url,  array $options = array())
    {
            $defaults = array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT => 4
            );

            $ch = curl_init();
            curl_setopt_array($ch, ($options + $defaults));
            if( ! $result = curl_exec($ch))
            {
                trigger_error(curl_error($ch));
            }
            curl_close($ch);
            return $result;
    }

    public function viewReport($date){

          $this->reportShow = false;
          $this->detailReport = true;
          $this->reportView =false;
          // echo $date;exit;  
        //   $result  = DB::select('SELECT * 
        //                         FROM track_locations 
        //                         INNER JOIN 
        //                         (SELECT MAX(id) as id,FLOOR(UNIX_TIMESTAMP(time)/(30 * 60)) AS timekey FROM track_locations where date BETWEEN "'.$this->from_date.'" and "'.$this->to_date.'" and user_id='.$this->user_id.'  GROUP BY timekey) last_updates 
        //                         ON last_updates.id = track_locations.id order by track_locations.id asc');

      $details =  TrackLocations::where('user_id','=',$this->user_id)->whereBetween('date',[$date, $date])
            ->get()->sortBy('id');
        
        $status = '';
            if($details){
                foreach($details as $key => $value){
                   
                    if($status != $value->status){
                            $url="https://maps.google.com/maps/api/geocode/json?latlng=".$value->latitude.",".$value->longitude."&key=".env('GOOGLEMAPAPI');
                            $curl_return=$this->curl_get($url);
                            $obj=json_decode($curl_return);
                            $result[$key]['date'] = $value->date;
                            $result[$key]['time'] = $value->time;
                            $result[$key]['address'] = $obj->results[0]->formatted_address;
                            $result[$key]['status'] = ucfirst($this->status[$value->status]);
                    }
                    $status = $value->status;
                }
            }
            $this->result = $result;
    }

    public function viewPath($user_id, $start, $end){
            echo $user_id;
            exit;
            $this->reportShow = false;
            $this->viewPath = true;
    }

    public function backToReports(){

        $this->reportShow = false;
        return $this->redirect('/reports');
    }

    public function goback(){
        $this->show = true;
        $this->detailReport = false;
        $this->generateWorkReport();
    }

}
