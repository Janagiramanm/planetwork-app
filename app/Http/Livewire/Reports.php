<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\UserRole;
use App\Models\TrackLocations;
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
        return view('livewire.reports.reports');
    }

    public function generateWorkReport(){
        $this->validate([
            'user_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $this->show =true;
        $this->detailReport = false;
        $this->result = null;
        


        $result = DB::table('track_locations')
        ->select(DB::raw('DATE(date) as date'))
        ->where('user_id','=', $this->user_id)
        ->whereBetween('date',[$this->from_date, $this->to_date])
        ->groupBy('date')
        ->get();

     
        if($result){
            $dateWiseData = [];
            foreach($result as $key => $value){
                 $dateWiseData[$value->date] =  DB::select('(SELECT * FROM track_locations where user_id = '.$this->user_id.' and date = "'.$value->date.'" ORDER BY id LIMIT 1)
                                                             UNION ALL
                                                             (SELECT * FROM track_locations where user_id = '.$this->user_id.' and date = "'.$value->date.'" ORDER BY id DESC LIMIT 1)');
            }
           ;
 
            if(!empty($dateWiseData)){
                foreach($dateWiseData as $key => $value){
                    $this->result[$key] = [
                                'date' => $value[0]->date,
                                'start_time' =>  $value[0]->time,
                                'end_time' => $value[1]->time,
                                'from_address' => $this->getAddress($value[0]->latitude,$value[0]->longitude),
                                'to_address' => $this->getAddress($value[1]->latitude,$value[1]->longitude)                               
                    ];
                }
            }
          
        }

        
        // $result  = DB::select('SELECT * 
        // FROM track_locations 
        // INNER JOIN 
        // (SELECT MAX(id) as id,date FROM track_locations where date BETWEEN "'.$this->from_date.'" and "'.$this->to_date.'" and user_id='.$this->user_id.'  GROUP BY date) last_updates 
        // ON last_updates.id = track_locations.id order by track_locations.id asc');

        // $this->result = null;

      
        //exit;
       
        // if($result){
        //     foreach($result as $key => $value){
        //         $url="https://maps.google.com/maps/api/geocode/json?latlng=".$value->latitude.",".$value->longitude."&key=".env('GOOGLEMAPAPI');
        //         $curl_return=$this->curl_get($url);
        //         $obj=json_decode($curl_return);

        //         if($value->status == 1){
        //             $status = "Start";
        //         }
        //         if($value->status == 2){
        //             $status = "Pause";
        //         }
        //         if($value->status == 3){
        //             $status = "Logout";
        //         }
        //         $this->result[$key] = [
        //                'date' => $value->date,
        //                'latitude' => $value->latitude,
        //                'longitude' => $value->longitude,
        //                'time' => $value->time,
        //                'address' =>  $obj->results[0]->formatted_address,
        //                'status'=> $value->status
        //         ];
        //     }
        // }
     
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
