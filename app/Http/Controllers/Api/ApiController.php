<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrackLocations;
use App\Models\WorkReport;
use App\Models\User;
use DB;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function trackLocation(Request $request){
         
           $data = $request->data;      
        //    $distance_km = 0.0;
           $travel = 0 ;
           if($data){
            // $insert = TrackLocations::insert($data);
               foreach($data as $key => $value){
                    $track = new TrackLocations();
                    $track->user_id = $value['user_id'];
                    $track->job_id = $value['job_id'];
                    $track->date = $value['date'];
                    $track->time = $value['time'];
                    $track->latitude = $value['latitude'];
                    $track->longitude = $value['longitude'];
                    $track->status = $value['status'];
                    $track->is_reached = $value['is_reached'];
                    // $track->distance_km = $distance_km;
                    $track->save();

                    $report = WorkReport::where('user_id','=', $value['user_id'])
                                        ->where('date', '=', $value['date'])
                                        ->orderBy('id','desc')
                                        ->latest()
                                        ->first();
                                        
                    // echo '<pre>';
                    // print_r($report);
                    if($report){
                        $distance =  $this->calculateDistanceBetweenTwoPoints($report->to_lat,$report->to_lng,$value['latitude'],$value['longitude']);
                        if($report->job_id == $value['job_id']){
                            $travelDistance = $report->travel_distance + $distance;
                            $workReport = WorkReport::find($report->id);
                            $workReport->travel_distance = (float)$travelDistance;
                            $workReport->to_lat = $value['latitude'];
                            $workReport->to_lng = $value['longitude'];
                            $workReport->to_address =  $this->getAddress( $value['latitude'],$value['longitude']);
                            $workReport->end = $value['time'];
                            if($track->is_reached == 'true'){
                               $workReport->is_reached = 'true';
                            }
                            $workReport->save();
                        }else{
                            $workReport = new WorkReport();
                            $workReport->date = $value['date'];
                            $workReport->user_id = $value['user_id'];
                            $workReport->job_id = $value['job_id'];
                            $workReport->travel_distance = $distance;
                            $workReport->from_lat = $value['latitude'];
                            $workReport->from_lng = $value['longitude'];
                            $workReport->to_lat = $value['latitude'];
                            $workReport->to_lng = $value['longitude'];
                            $workReport->start = $value['time'];
                            $workReport->end = $value['time'];
                            if($track->is_reached == 'true'){
                                $workReport->is_reached = 'true';
                             }
                            $workReport->to_address =  $this->getAddress( $value['latitude'],$value['longitude']);
                            $workReport->save();
                        }
                       
                    //    echo  $report->travel_distance.'==='.$distance.'+++++'.$report->travel_distance + $distance."<br>";
                       
                       
                    }
                    if(!$report){
                            $workReport = new WorkReport();
                            $workReport->date = $value['date'];
                            $workReport->user_id = $value['user_id'];
                            $workReport->job_id = $value['job_id'];
                            $workReport->travel_distance = $travel;
                            $workReport->from_lat = $value['latitude'];
                            $workReport->from_lng = $value['longitude'];
                            $workReport->to_lat = $value['latitude'];
                            $workReport->to_lng = $value['longitude'];
                            $workReport->from_address = $this->getAddress( $value['latitude'],$value['longitude']);
                            $workReport->start = $value['time'];
                            $workReport->end = $value['time'];
                            $workReport->save();
                    }

             }
            // if($insert){
                return [
                    'status' => 1,
                    'message' => 'Successfully Inserted.'
               ];
            // }
               
           }

           return [
             'status' => 0,
             'message' => 'Empty data is coming .'
            ];
           
    }

    public function userLocation(Request $request){

           $userLocations = TrackLocations::where('date','=','2021-10-25')->where('user_id','=',3)->get();
         
           return [
            'status' => 1,
            'data' => $userLocations
        ];
    }

    public function getUserPath(Request $request){

        $date = $request->date;
        $user_id = $request->user_id;
        $start_time = $request->start_time;
        $end_time = $request->end_time;

        $result = TrackLocations::where('date', '=', $date)
        ->where('user_id', '=', $user_id)
        ->whereBetween('time',[$start_time,$end_time])
        ->orderBy('created_at', 'asc')
        ->get();
        if(!$result){
            return [
                'status' => 0,
                'message' => 'No data found'
            ];
       
        }

        return [
            'status' => 1,
            'data' => $result
        ];
   
    
    }

    public function workReport(Request $request){
       $from_date = $request->from_date !='' ? $request->from_date : date('Y-m-d', strtotime('-10 days'));
       $to_date = $request->to_date !='' ? $request->to_date :  date('Y-m-d');
       $user_id = $request->user_id;
      //     $user_id = 2;


      $res = WorkReport::where('user_id','=', $user_id)
      ->whereBetween('date',[$from_date, $to_date])
      ->get();

        $dateWiseData = [];
        if($res){

        foreach($res as $key => $value){
            $dateWiseData[$key]['date'] = $value->date;
            
            $dateWiseData[$key]['customer_name'] = '';
            $dateWiseData[$key]['job_name'] = '';
            $dateWiseData[$key]['job_status'] = '';
            $dateWiseData[$key]['sr_no'] = '';

            $dateWiseData[$key]['user_id'] = $value->user_id;
            $dateWiseData[$key]['user_name'] = $value->user->name;
            if($value->job_id != 0){
                $dateWiseData[$key]['customer_name'] = $value->job->customer->first_name;
                $dateWiseData[$key]['job_name'] = $value->job->task->name;
                $dateWiseData[$key]['job_status'] = $value->job->employees;
                $dateWiseData[$key]['sr_no'] = $value->job->sr_no;
                $dateWiseData[$key]['travel_distance'] = $value->travel_distance;
                $dateWiseData[$key]['from_address'] = $this->getAddress($value->from_lat,$value->from_lng);
                $dateWiseData[$key]['to_address'] = $this->getAddress($value->to_lat,$value->to_lng);
                $dateWiseData[$key]['start'] = $value->start;
                $dateWiseData[$key]['end'] = $value->end;
            }
        }

        }
        // $this->result = $dateWiseData;

                         
    //   $result = DB::table('track_locations')
    //   ->select(DB::raw('DATE(date) as date'))
    //   ->where('user_id','=', $user_id)
    //   ->groupBy('date')
    //   ->get();

     
    //   $final_result = [];
    //    if($result){
    //        $dateWiseData = [];
    //        foreach($result as $key => $value){

    //             // $a = TrackLocations::where('user_id', '=', $user_id)
    //             //      ->where('date', '=' , $value->date)->orderBy('id','asc')->limit(1);

    //             // $dateWiseData[$value->date] = TrackLocations::where('user_id', '=', $user_id)
    //             //      ->where('date', '=' , $value->date)->orderBy('id','desc')->limit(1)->union($a)->get();


    //             // $table = DB::table("track_locations")
    //             //         ->select('*')->where('date', '=' , $value->date)->orderBy('id','asc')->skip(0)->limit(1);

    //             // $dateWiseData[$value->date] =       DB::table("track_locations")
    //             //         ->select('*')
    //             //         ->union($table)
    //             //         ->where('date', '=' , $value->date)->orderBy('id','desc')->skip(0)->limit(1)
    //             //         ->get();

    //             $dateWiseData[$value->date] =  DB::select('(SELECT * FROM track_locations where user_id = '.$user_id.' and date = "'.$value->date.'" ORDER BY id LIMIT 1)
    //                                                         UNION ALL
    //                                                         (SELECT * FROM track_locations where user_id = '.$user_id.' and date = "'.$value->date.'" ORDER BY id DESC LIMIT 1)');
    //        }

    //        if(!empty($dateWiseData)){
    //            foreach($dateWiseData as $key => $value){

    //                 $final_result[] = [
    //                            'date' => $value[0]->date,
    //                            'start_time' =>  $value[0]->time,
    //                            'end_time' => $value[1]->time,
    //                            'from_lat' => $value[0]->latitude,
    //                            'from_lng' => $value[0]->longitude,
    //                            'to_lat' => $value[1]->latitude,
    //                            'to_lng' => $value[1]->longitude,
    //                            'distance' => round($this->calculateDistanceBetweenTwoPoints($value[0]->latitude,$value[0]->longitude, $value[1]->latitude, $value[1]->longitude, 'KM'),1)
    //                 ];
    //            }
    //        }
    //    }
      
      
       
        if( empty($dateWiseData)){
            return [
                'status' => 0,
                'message' => 'No data found'
            ];
        }

       
        return [
            'status' => 1,
            'data' => $dateWiseData
        ];

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

    public function workReportDetails(Request $request){
        $date = $request->date;
        $user_id = $request->user_id;

        $res = WorkReport::where('user_id','=', $user_id)
        ->where('date',[$date])
        ->get();

        $dateWiseData = [];
        if($res){
            foreach($res as $key => $value){
                $dateWiseData[$key]['date'] = $value->date;
                $dateWiseData[$key]['customer_name'] = '';
                $dateWiseData[$key]['job_name'] = '';
                $dateWiseData[$key]['job_status'] = '';
                $dateWiseData[$key]['sr_no'] = '';

                $dateWiseData[$key]['user_id'] = $value->user_id;
                $dateWiseData[$key]['user_name'] = $value->user->name;
                if($value->job_id != 0){
                    $dateWiseData[$key]['customer_name'] = $value->job->customer->first_name;
                    $dateWiseData[$key]['job_name'] = $value->job->task->name;
                    $dateWiseData[$key]['job_status'] = $value->job->employees;
                    $dateWiseData[$key]['sr_no'] = $value->job->sr_no;
                }
                    $dateWiseData[$key]['travel_distance'] = $value->travel_distance;
                    $dateWiseData[$key]['from_address'] = $this->getAddress($value->from_lat,$value->from_lng);
                    $dateWiseData[$key]['to_address'] = $this->getAddress($value->to_lat,$value->to_lng);
                    $dateWiseData[$key]['start'] = $value->start;
                    $dateWiseData[$key]['end'] = $value->end;
            }
        }
        // $this->result = $dateWiseData;

        return $dateWiseData;
       
      /*  $result  = DB::select('SELECT * 
                               FROM track_locations 
                               INNER JOIN 
                               (SELECT MAX(id) as id,FLOOR(UNIX_TIMESTAMP(time)/(14 * 60)) AS timekey FROM track_locations where  user_id='.$user_id.' and date = "'.$date.'"  GROUP BY timekey) last_updates 
                               ON last_updates.id = track_locations.id');

        $pause = TrackLocations::where('status', '=', 2)
        ->where('user_id','=', $user_id)
        ->where('date','=', $date)->get();

        $count = count($result) - 1;

       // $distance = round($this->point2point_distance($result[0]->latitude,$result[0]->longitude, $result[$count]->latitude, $result[$count]->longitude,'K'), 2);
        $distance = round($this->calculateDistanceBetweenTwoPoints($result[0]->latitude,$result[0]->longitude, $result[$count]->latitude, $result[$count]->longitude, 'KM'),1);
        $ideal_locations = $this->calculateDistanceBetweenTwoPoints($result[0]->latitude,$result[0]->longitude, $result[$count]->latitude, $result[$count]->longitude, 'MT');
        $ideal = [];
        
        if($distance < 20){
             // $ideal[] = $lat2.','.$lng2;
              $ideal[] = ['lat'=> $result[$count]->latitude, 'lng'=>$result[$count]->longitude];
              
          }
          
        if(!$result){
             return [
                 'status' => 0,
                 'message' => 'No data found'
             ];
        }
 
        return [
             'status' => 1,
             'distance' => $distance,
             'travelhistory' => $result,
             'pauselocations' => $pause,            
             'ideal_locations' => $ideal
         ];
         */
 
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

    public function updateFcmToken(Request $request){

        $user = User::find($request->user_id);
        if($user){
        $user->fcm_token = $request->token;
        $user->device_model = $request->model;
        if($user->save()){
            return [
                'status' => 1,
                'message' => "Successully Updated"
            ];
        }
        return [
            'status' => 0,
            'message' => "Something went wrong"
        ];
    }
    return [
        'status' => 0,
        'message' => "No user found"
    ];


    }


}
