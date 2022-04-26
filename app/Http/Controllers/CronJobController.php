<?php

namespace App\Http\Controllers;
use App\Models\TrackLocations;
use App\Models\WorkReport;
use Carbon\Carbon;
use DB;

use Illuminate\Http\Request;

class CronJobController extends Controller
{
    public function cronWrokReport(){

        $date = Carbon::now()->format('Y-m-d');
        $result = TrackLocations::where('date',$date)->get();
        if($result){
            $dateWiseData = [];
            $i = 0;
            $totTravel = 0;
            $cnt = count($result) -1;
            foreach($result as $key => $value){

                 $dateWiseData[$value->date]['customer_name'] = '';
                 $dateWiseData[$value->date]['job'] = '';
                 $dateWiseData[$value->date]['status'] = '';
                 $dateWiseData[$value->date]['sr_no'] = '';
                 $dateWiseData[$value->date]['from_address'] = '';
                 $dateWiseData[$value->date]['to_address'] = '';

                 $dateWiseData[$value->date]['latitude'][] = $value->latitude;
                 $dateWiseData[$value->date]['longitude'][] = $value->longitude;
                 $dateWiseData[$value->date]['date'] = $value->date;
                 $dateWiseData[$value->date]['user_id'] = $value->user_id;
                 $dateWiseData[$value->date]['user_name'] = $value->user->name;
                 $dateWiseData[$value->date]['travel'] = $totTravel;
                 if($value->job_id != 0){
                    $dateWiseData[$value->date]['customer_name'] = $value->job->customer->first_name;
                    $dateWiseData[$value->date]['job_id'] = $value->job_id;
                    $dateWiseData[$value->date]['job'] = $value->job->task->name;
                    $dateWiseData[$value->date]['status'] = $value->job->status;
                    $dateWiseData[$value->date]['sr_no'] = $value->job->sr_no;
                }
                    $dateWiseData[$value->date]['track'] = $this->getDateWiseData($value->user_id,$value->date,$value->job_id);
                    $dateWiseData[$value->date]['start'] = $dateWiseData[$value->date]['track'][0]->time;
                    $dateWiseData[$value->date]['end'] = $dateWiseData[$value->date]['track'][1]->time;

               
                $i++;
            }

            foreach($dateWiseData as $key => $value){
                    $cnt = count($value['latitude']);
                    for($k = 0; $k <= $cnt; $k++ ){
                        if(isset($value['latitude'][$k+1])){
                            $travel = $this->calculateDistanceBetweenTwoPoints($value['latitude'][$k], $value['longitude'][$k], $value['latitude'][$k+1], $value['longitude'][$k+1]);
                            $totTravel += is_nan($travel) ? 0 : $travel;
                        }
                    }

                    $isExist = WorkReport::where('date','=', $date)
                                ->where('user_id','=', $value['user_id'])
                                ->where('job_id','=',$value['job_id'])
                                ->first();
                    $from_address = $this->getAddress($value['track'][0]->latitude,$value['track'][0]->longitude);
                    $to_address = $this->getAddress($value['track'][1]->latitude,$value['track'][1]->longitude);
                                
                    if(!$isExist){
                            $workReport = new WorkReport();
                            $workReport->date = $value['date'];
                            $workReport->user_id = $value['user_id'];
                            $workReport->job_id = $value['user_id'];
                            $workReport->user_name = $value['user_name'];
                            $workReport->customer_name =$value['customer_name'];
                            $workReport->job_name = $value['job'];
                            $workReport->status = $value['status'];
                            $workReport->sr_no = $value['sr_no'];
                            $workReport->travel_distance = $totTravel;
                            $workReport->from_address = $from_address;
                            $workReport->to_address = $to_address;
                            $workReport->start = $value['start'];
                            $workReport->end = $value['end'];
                            $workReport->save();
                               
                    }else{
                            $workReport = WorkReport::find($isExist->id);
                            $workReport->date = $value['date'];
                            $workReport->user_id = $value['user_id'];
                            $workReport->job_id = $value['user_id'];
                            $workReport->user_name = $value['user_name'];
                            $workReport->customer_name =$value['customer_name'];
                            $workReport->job_name = $value['job'];
                            $workReport->status = $value['status'];
                            $workReport->sr_no = $value['sr_no'];
                            $workReport->travel_distance = $totTravel;
                            $workReport->from_address = $from_address;
                            $workReport->to_address = $to_address;
                            $workReport->start = $value['start'];
                            $workReport->end = $value['end'];
                            $workReport->save();
                               
                    }
                   
            }
            return 'success';
          
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
}
