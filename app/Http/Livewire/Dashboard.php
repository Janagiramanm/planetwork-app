<?php

namespace App\Http\Livewire;

use App\Models\TrackLocations;
use App\Models\User;
use App\Models\UserRole;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\WorkReport;
use DB;
use DateTime;
use URL;



class Dashboard extends Component
{
    public $locations,$lat,$lng, $user_id, $job_date, $details, $date, $reslatLong, $wayPoints,
           $apiKey, $count, $distance, $user_name,$from_address,$to_address,$ideal,$ideal_locations,
           $from_date,$to_date,$customer_type, $business, $individual, $show, $baseUrl, $new_time, $insert_date,
           $threeHours,$thirtyMinutes,$totalDuration, $report, $date_display, $uId;
    public $latLong = [];
    public $detailMap,$mapPath = false;
      
    protected $listeners = [
        'getDetailPath', 'getDetailMapData'
   ];
    public $getDetailMapData = '\App\Http\Livewire\Dashboard::getDetailMapData';
   
    public function render()
    {
        $curdate = date('Y-m-d'); //'2022-05-18'; //    
        
        $user_id = isset($_GET['user_id'])!='' ? $_GET['user_id']:'';
        $date = isset($_GET['date'])!='' ? $_GET['date']:'';
       
        $this->users = User::whereHas('role', function ($query) {
            $query->where('role_id', '=', '3');
         })->get();

       
        $usersList= User::when($user_id, function ($q) use ($user_id) {
                         $q->where('id', $user_id);
                })->whereHas('role', function ($query) {
                         $query->where('role_id', '=', '3');
                })->get();

        $trackIds = [];
        $users = [];
        $dateCondition ='';
       
        if($usersList){
            foreach($usersList as $user){

                $users[$user->id]['name'] = $user->name;
                $users[$user->id]['lat']  = $user->employeeDetail->latitude ?? 13.02313732; 
                $users[$user->id]['lng']  = $user->employeeDetail->longitude ?? 77.6471962; 
               // if($date !=''){
                    $dateCondition = "and date = '".$curdate."'"; 
               // }
                $users[$user->id]['location'] = DB::select('SELECT * 
                                        FROM track_locations 
                                        INNER JOIN 
                                        (SELECT MAX(id) as id FROM track_locations where user_id = '. $user->id .'  and status = 1 '.$dateCondition.') last_updates 
                                        ON last_updates.id = track_locations.id');
            }

            $res[] = ['lat'=>'13.00232321', 'lng'=>'77.9899200', 'details'=>'', 'user_id'=>'', 'date'=>$curdate, 'markerColor'=>''];
            $this->lat = '13.00232321';
            $this->lng = '77.9899200';
            if($users){
                foreach($users as $key => $value){
                     
                    if(isset($value['location'][0]->id)){
                       $insert_date = date('d-m-Y H:i:s',strtotime($value['location'][0]->date.''.$value['location'][0]->time));
                       $thirtyMinutes = date("Y-m-d H:i:s", strtotime('-30 minutes', strtotime($curdate)));
                       $threeHours = date("Y-m-d H:i:s", strtotime('-3 hours', strtotime($curdate))); // $now + 3 hours
                       $insert_date = Carbon::parse($insert_date);
                       
                       if(Carbon::now()->subHours(3) > $insert_date ){
                            $marker = "red";
                        }else if(Carbon::now()->subMinutes(30) > $insert_date ){
                            $marker = "orange";
                        }else{
                            $marker = "green";
                        }
                   
                        $date = date('d-m-Y',strtotime($value['location'][0]->date));

                        $this->lat = isset($value['location'][0]->latitude) ? $value['location'][0]->latitude : $value['lat'];
                        $this->lng = isset($value['location'][0]->longitude) ? $value['location'][0]->longitude : $value['lng'];
                        $details = '<b>'.$value['name'].'</b><br> Date : '.$date.'<br> Time :'. $value['location'][0]->time;
                        $res[] = ['lat'=>$this->lat, 'lng'=>$this->lng, 'details'=>$details, 'user_id'=>$key, 'date'=>$date, 'markerColor'=>$marker];
                    }
                }
            }

               
        }
       
        $this->user_id = $user_id ?? '';
        $this->latLong = json_encode($res, JSON_NUMERIC_CHECK);
     
        $this->baseUrl = URL::to('/');

        return view('livewire.dashboard.dashboard');
    }

    public function getDetailPath($user_id, $date){
         $this->detailMap = true;
         $this->date = date('Y-m-d',strtotime($date));
         $this->user_id = $user_id; 
         $this->uId  = $user_id;
         $this->report =  WorkReport::where('user_id','=', $this->user_id)
         ->where('created_at','LIKE',$this->date.'%')->get();

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
        $this->mapPath = true;
        $this->apiKey = env('GOOGLEMAPAPI');
        // $idealLocation = $this->idealLocations($this->uId, $this->date);
        $this->locations = TrackLocations::where('date', '=', $this->date)
        ->where('user_id', '=', $this->uId)
        // ->whereBetween('time',[$start_time,$end_time])
        ->orderBy('time', 'asc')
        ->get();

        if(!$this->locations ->isEmpty()){
            foreach($this->locations as $key => $value){
                $details = '<b>'.$value->user->name.'</b><br> Date : '.date('d-m-Y',strtotime($value->date)) 
                          .'<br> Time : '. $value->time;
                $reslatLong[] = ['lat'=>$value->latitude, 'lng'=>$value->longitude, 'time'=>$value->time];
            }
            $this->reslatLong =  json_encode($reslatLong, JSON_NUMERIC_CHECK);
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
                     
                     if($distance < 50){
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
             $decimalPoints = 3;
         }
        
         $distanceUnit = strtolower($distanceUnit);
         $pointDifference = $longitudeOne - $longitudeTwo;
         $toSin = (sin(deg2rad($latitudeOne)) * sin(deg2rad($latitudeTwo))) + (cos(deg2rad($latitudeOne)) * cos(deg2rad($latitudeTwo)) * cos(deg2rad($pointDifference)));
         $toAcos = acos($toSin);
         $toRad2Deg = rad2deg($toAcos);
 
         $toMiles  =  $toRad2Deg * 60 * 1.1515;
         $toKilometers = $toMiles * 1.609344;
          if(is_float($toKilometers)){
             $toKilometers  = ($round == true ? round($toKilometers) : round($toKilometers, $decimalPoints));
          }else{
               $toKilometers = 0.00;
          }
          return $toKilometers;
 
             
 
     }

    
}
