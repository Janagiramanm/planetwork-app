<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\UserRole;
use App\Models\TrackLocations;
use App\Models\AssignJobEmployee;
use Carbon\Carbon;
use DB;
use DateTime;

class Reports extends Component
{
    public $user_id, $from_date,$to_date;
    public $result = [];
    public $show, $processing, $detailReport = false;
    public $status = ['logout','login','pause'];
    public function render()
    {

        $this->users = UserRole::where('role_id','=',3)->get();
        $this->from_date = $this->from_date? $this->from_date : Carbon::now()->format('Y-m-d');
        $this->to_date = $this->to_date? $this->to_date : Carbon::now()->format('Y-m-d'); 
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
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $this->show =true;
        $this->detailReport = false;
        $this->result = null;
        


        $result = TrackLocations::where('user_id','=', $this->user_id)
                    ->whereBetween('date',[$this->from_date, $this->to_date])
                    // ->select(DB::raw('DATE(date) as date,user_id,job_id'))
                   // ->groupBy('date','user_id','job_id')
                    ->get();

        $this->from_date = $this->from_date? $this->from_date : Carbon::now()->format('Y-m-d');
        $this->to_date = $this->to_date? $this->to_date : Carbon::now()->format('Y-m-d'); 
       

        // $result = DB::table('track_locations')
        // ->select(DB::raw('DATE(date) as date'))
        // ->where('user_id','=', $this->user_id)
        // ->whereBetween('date',[$this->from_date, $this->to_date])
        // ->groupBy('date')
        // ->get();

      
        // echo '<pre>';
        // print_r($result);

      
        if($result){
            $dateWiseData = [];
            $i = 0;
            $travel = 0;
            foreach($result as $key => $value){

                $dateWiseData[$value->date]['customer_name'] = '';
                $dateWiseData[$value->date]['job'] = '';
                $dateWiseData[$value->date]['status'] = '';
                $dateWiseData[$value->date]['sr_no'] = '';

                if(isset($result[$i+1]->latitude)){
                    $latitudeOne = $result[$i]->latitude;
                    $longitudeOne = $result[$i]->longitude;
    
                    $latitudeTwo = $result[$i+1]->latitude;
                    $longitudeTwo = $result[$i+1]->longitude;
                    
                    $travel += $this->calculateDistanceBetweenTwoPoints($latitudeOne, $longitudeOne, $latitudeTwo, $longitudeTwo);
                 }
                
                 $dateWiseData[$value->date]['travel'] = $travel;
                 $dateWiseData[$value->date]['date'] = $value->date;
                 $dateWiseData[$value->date]['user_id'] = $value->user_id;
                 $dateWiseData[$value->date]['user_name'] = $value->user->name;
                 if($value->job_id != 0){
                    $dateWiseData[$value->date]['customer_name'] = $value->job->customer->first_name;
                    $dateWiseData[$value->date]['job'] = $value->job->task->name;
                    $dateWiseData[$value->date]['status'] = $value->job->status;
                    $dateWiseData[$value->date]['sr_no'] = $value->job->sr_no;
                }
                 $dateWiseData[$value->date]['track'] = $this->getDateWiseData($this->user_id,$value->date,$value->job_id);
                 if($i==0){
                    $dateWiseData[$value->date]['from_address'] = $this->getAddress($dateWiseData[$value->date]['track'][0]->latitude,$dateWiseData[$value->date]['track'][0]->longitude);
                 }
                    $dateWiseData[$value->date]['to_address'] = $this->getAddress($dateWiseData[$value->date]['track'][1]->latitude,$dateWiseData[$value->date]['track'][1]->longitude);
                    $dateWiseData[$value->date]['start'] = $dateWiseData[$value->date]['track'][0]->time;
                    $dateWiseData[$value->date]['end'] = $dateWiseData[$value->date]['track'][1]->time;

               
                // 
                
                // print_r($dateData);
              
                // $this->result[$value->date] = [
                //     'sr_no' => $value['sr_no'],
                //     'date' => $value[0]->date,
                //     'user_id' => $value[0]->user_id,
                //     'user_name' => $value['user_name'],
                //     'customer_name' => $value['customer_name'],
                //     'job' => $value['job'],
                //     'status' => $value['status'],
                //     'start_time' =>  $value[0]->time,
                //     'end_time' => $value[1]->time,
                //     'from_address' => $this->getAddress($value[0]->latitude,$value[0]->longitude),
                //     ///'to_address' => $this->getAddress($value[1]->latitude,$value[1]->longitude)                               
                //     'to_address'=> $value['to_address']
                // ];
                $i++;
            }

           // exit;

            // echo '<pre>';
            // print_r($dateWiseData);
         //  $dateWiseData['distance'] = $travel;

            $this->result = $dateWiseData;
           
            // if(!empty($dateWiseData)){
            //     foreach($dateWiseData as $key => $value){
            //             $this->result[$key] = [
            //                 'sr_no' => $value['sr_no'],
            //                 'date' => $key,
            //                 'user_id' => $value[0]->user_id,
            //                 'user_name' => $value['user_name'],
            //                 'customer_name' => $value['customer_name'],
            //                 'job' => $value['job'],
            //                 'status' => $value['status'],
            //                 'start_time' =>  $value[0]->time,
            //                 'end_time' => $value[1]->time,
            //                 'travel' => array_sum($travel),
            //                 'from_address' => $this->getAddress($value[0]->latitude,$value[0]->longitude),
            //                 ///'to_address' => $this->getAddress($value[1]->latitude,$value[1]->longitude)                               
            //                 'to_address'=> $value['to_address']
            //             ];
            //     }
            // }

            // print_r($this->result);
          
        }
       
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

          $this->show = false;
          $this->detailReport = true;
         //  echo $date;exit;  
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

    public function backToReports(){

       
        return $this->redirect('/reports');
    }

    public function goback(){
        $this->show = true;
        $this->detailReport = false;
        $this->generateWorkReport();
    }

}
