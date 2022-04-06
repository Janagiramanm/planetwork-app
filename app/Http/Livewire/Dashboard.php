<?php

namespace App\Http\Livewire;

use App\Models\TrackLocations;
use App\Models\UserRole;
use Livewire\Component;
use Carbon\Carbon;
use DB;
use DateTime;



class Dashboard extends Component
{
    public $locations,$lat,$lng, $user_id, $job_date, $details, $date, $reslatLong, $wayPoints,
           $apiKey, $count, $distance, $user_name,$from_address,$to_address,$ideal,$ideal_locations,
           $from_date,$to_date,$customer_type, $business, $individual, $show;
    public $latLong = [];
    public $detailMap = false;
      
    protected $listeners = [
        'getDetailPath'
   ];
   
    public function render()
    {
        $curdate = date('Y-m-d');
        
        // $startdate = isset( $this->from_date) ? $this->from_date:$curdate;
        // $enddate = isset($this->to_date) ? $this->to_date:$curdate;
        $this->user_id = isset($_GET['user_id'])!='' ? $_GET['user_id']:'';
        $this->from_date = isset($_GET['from_date']) ? $_GET['from_date']:$curdate;
        $this->to_date = isset($_GET['to_date']) ? $_GET['to_date']:$curdate;
        $userCondition = '';
        if($this->user_id!=''){
           $userCondition = " and user_id = ".$this->user_id;
        }
    
       

        //$this->customers = Customer::get();
      //  $this->business = Customer::where('customer_type','=','BUSINESS')->get();
        $this->users = UserRole::where('role_id','=',3)->get();

        ///echo $startdate.'---'.$enddate;exit;
        $trackIds = [];
        $users  = DB::select('SELECT * 
                                        FROM track_locations 
                                        INNER JOIN 
                                        (SELECT MAX(id) as id FROM track_locations where date BETWEEN "'.$this->from_date.'" and "'.$this->to_date.'" '.$userCondition.'  and status = 1  GROUP BY user_id,date) last_updates 
                                        ON last_updates.id = track_locations.id');

        if($users){
            foreach($users as $key => $user){
                $trackIds[] = $user->id;
            }
        }                                    

        
        
        // $this->locations  = TrackLocations::whereIn('time', function($query) use ($curdate) {
        //                             $query->selectRaw('max(`time`)')
        //                             ->from('track_locations')
        //                             ->where('date', '=', $curdate)
        //                             ->groupBy('user_id')->orderBy('time', 'asc');
        //                         })->select('user_id', 'date', 'time', 'latitude', 'longitude')
        //                         ->where('date', '=', $curdate)
        //                         ->orderBy('time', 'asc')
        //                         ->get();

       $this->locations  = TrackLocations::whereIn('id',$trackIds)->get();

     
      
        $res = [];
        if($this->locations->isEmpty()){
            $this->lat = 13.02313732;
            $this->lng = 77.6471962;
        }
        if($this->locations){
            foreach($this->locations as $key => $value){
                

                 $details = '<b>'.$value->user->name.'</b><br> Date : '.date('d-m-Y',strtotime($value->date)) 
                          .'<br> Time : '. $value->time;
               
                $res[] = ['lat'=>$value->latitude, 'lng'=>$value->longitude, 'details'=>$details, 'user_id'=>$value->user_id, 'date'=>$value->date];
                //$res[] = [$details, $value->latitude, $value->longitude, $key, $value->user_id, $value->date];
                // $res[] = [$details, $value->latitude, $value->longitude, $key];
                $this->lat =  $value->latitude;
                $this->lng = $value->longitude;
                $this->user_id = $value->user_id;
                $this->job_date = $value->date;
               
                
            }
        }
        $this->latLong = json_encode($res, JSON_NUMERIC_CHECK);

        return view('livewire.dashboard.dashboard');
    }

    public function getDetailPath($user_id, $date){
           $this->detailMap = true;
        //    $user_id = 2;
        //    $date = "2021-10-26";
        //    $start_time = "18:50";
        //    $end_time ="23:10";

        // SELECT * 
        //                                 FROM track_locations 
        //                                 INNER JOIN 
        //                                 (SELECT MAX(id) as id,FLOOR(UNIX_TIMESTAMP(time)/(15 * 60)) AS timekey FROM track_locations where status = 1  GROUP BY timekey) last_updates 
        //                                 ON last_updates.id = track_locations.id

        // $users  = DB::select('SELECT * 
        //                         FROM track_locations 
        //                         INNER JOIN 
        //                         (SELECT MAX(id) as id,FLOOR(UNIX_TIMESTAMP(time)/(15 * 60)) AS timekey FROM track_locations where status = 1  GROUP BY timekey) last_updates 
        //                         ON last_updates.id = track_locations.id');

        // if($users){
        //     foreach($users as $key => $user){
        //         $trackIds[] = $user->id;
        //     }
        // }  

        // $this->locations  = TrackLocations::whereIn('id',$trackIds)
        
        // ->get();

        $idealLocation = $this->idealLocations($user_id, $date);
        $this->locations = TrackLocations::where('date', '=', $date)
        ->where('user_id', '=', $user_id)
        // ->whereBetween('time',[$start_time,$end_time])
        ->orderBy('time', 'asc')
        ->get();








            // $this->locations = TrackLocations::select('user_id', 'date', 'time' , 'latitude', 'longitude')
            // ->where('date', '=', $date)
            // ->where('user_id', '=', $user_id)
            // ->whereBetween('time',[$start_time,$end_time])
            // // ->groupBy('latitude','longitude','user_id', 'date')
            // ->orderBy('created_at', 'asc')
           
            // ->get();

            // echo '<pre>';
            // print_r($this->locations->count());
            // print_r($this->locations);
            // exit;
        $res = [];
        if($this->locations){
            foreach($this->locations as $key => $value){
                
                $details = '<b>'.$value->user->name.'</b><br> Date : '.date('d-m-Y',strtotime($value->date)) 
                          .'<br> Time : '. $value->time;

                
                    
                $reslatLong[] = ['lat'=>$value->latitude, 'lng'=>$value->longitude, 'time'=>$value->time];
                // $reslatLong[] = ['lat'=>$value->latitude, 'lng'=>$value->longitude, 'detail'=>$details];
                // $res[] = [$details, $value->latitude, $value->longitude, $key];
                // $wayPoints[] = $value->latitude.','.$value->longitude;
                $this->lat =  $value->latitude;
                $this->lng = $value->longitude;
                $this->user_id = $value->user_id;
                $this->job_date = $value->date;
                $this->user_name = $value->user->name;
            }
          //  $this->wayPoints = json_encode($wayPoints, JSON_NUMERIC_CHECK);
            $this->date = $date;
            $this->reslatLong = json_encode($reslatLong, JSON_NUMERIC_CHECK);
            $this->apiKey = env('GOOGLEMAPAPI');
            $count = $this->locations->count() -1;
            $this->from_address = $this->getAddressByLatLng($this->locations[0]->latitude,$this->locations[0]->longitude);
            $this->to_address = $this->getAddressByLatLng($this->locations[$count]->latitude,$this->locations[$count]->longitude);
            $this->distance = round($this->point2point_distance($this->locations[0]->latitude,$this->locations[0]->longitude, $this->locations[$count]->latitude, $this->locations[$count]->longitude,'K'), 2);
           
           
        }
        
    }

    public function back(){
        $this->detailMap = false;
       // $this->render();
        return redirect(request()->header('Referer'));
    }

      
      public function point2point_distance($lat1, $lon1, $lat2, $lon2, $unit) 
      { 
          $theta = $lon1 - $lon2; 
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
          $dist = acos($dist); 
          $dist = rad2deg($dist); 
          $miles = $dist * 60 * 1.1515;
          $unit = strtoupper($unit);

         /// echo $miles;
  
          if ($unit == "K") 
          {
           
              return ($miles * 1.609344); 
          } 
          else if ($unit == "N") 
          {
              return ($miles * 0.8684);
          } 
          else if ($unit == "M"){
              return ($miles * 1609.34);
          }
          else 
          {
             return $miles;
        }
      }  
      
      public function getAddressByLatLng($lat, $lng){
        
        $latLng = $lat.','.$lng;
        $this->apiKey = env('GOOGLEMAPAPI');
        $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latLng.'&sensor=false&key='.$this->apiKey);
        // $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        if(isset($output->results[0]->formatted_address)){
           return $output->results[0]->formatted_address;
        }
        return null;
       
      }

      public function idealLocations($user_id, $date){
                   $result  = DB::select('SELECT * 
                                            FROM track_locations 
                                            INNER JOIN 
                                            (SELECT MAX(id) as id,FLOOR(UNIX_TIMESTAMP(time)/(5 * 60)) AS timekey FROM track_locations where status = 1 and user_id='.$user_id.' and date = "'.$date.'"  GROUP BY timekey) last_updates 
                                            ON last_updates.id = track_locations.id');
            
            
             
              $count = count($result);
              $ideal = [];
             if($result){
                 foreach($result as $key => $value){

                      if($key != $count-1){
                        $lat1 = $value->latitude;
                        $lng1 = $value->longitude;
                        $lat2 = $result[$key+1]->latitude;
                        $lng2 = $result[$key+1]->longitude;
                        // $time1 = new DateTime($value->time);
                        // $time2 = new DateTime($result[$key+1]->time);
                        // $halt_time = $time1->diff($time2);
                       // $distance =  $this->point2point_distance($lat1, $lng1, $lat2, $lng2, 'M');
                        $distance =  $this->calculateDistanceBetweenTwoPoints($lat1, $lng1, $lat2, $lng2, 'MT');
                     
                     if($distance < 20){
                          // $ideal[] = $lat2.','.$lng2;
                           $ideal[] = ['lat'=> $lat2, 'lng'=>$lng2];
                           
                       }
                       
                        
                      }

             }
                $this->ideal_locations = json_encode($ideal, JSON_NUMERIC_CHECK);
                // echo '<pre>';
                // print_r($this->ideal_locations);
                // exit;
             }

      }

     public function searchFilter(){

       
        $this->render();
     }
   
     public function backToDashboard(){
        return $this->redirect('/dashboard');
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
}
