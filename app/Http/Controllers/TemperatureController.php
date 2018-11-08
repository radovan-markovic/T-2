<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\City;
use App\Hour;
use App\Temperature;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\HomeController;

class TemperatureController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user= Auth::user();
            
            if ($this->user->isAdmin()){
                return redirect('/admin');
            }
            return $next($request);
        });
    }
    /**
     * Get temperature of the selected city.
     *
     */
    public function getTemperature(Request $request)
    {

        $jsonurl = "http://apidev.accuweather.com/currentconditions/v1/".$request['city_code'].".json?language=en&apikey=hoArfRosT1215";
        $json = file_get_contents($jsonurl);
        $weather = json_decode($json);

        $current_temp = $this->fahrenheitToCelsius($weather[0]->Temperature->Imperial->Value);

        $response = array(
            'status' => 'success',
            'temp' => $current_temp
        );
        
        return response()->json($response);   
    }

    /**
     * Get temperatures for next 12 hours
     *
     */
    public function getAllTemperatures(Request $request){
         
      $jsonurl = "http://dataservice.accuweather.com/forecasts/v1/hourly/12hour/".$request['city_code']."?apikey=aO8HXPEzm9q865JX7CZ80C66IIGGUjj9";
        $json = file_get_contents($jsonurl);
        $weather = json_decode($json);

        foreach($weather as $key=> $value){
            
            $temperatures[$key]['time'] = substr($weather[$key]->DateTime, 11, 5);
            $temperatures[$key]['temperature'] = $this->fahrenheitToCelsius($weather[$key]->Temperature->Value);

        }

        $response = array(
            'status' => 'success',
            'all_temp' => $temperatures
        );
        
        return response()->json($response);
     }

     //Convert Fahrenheit to Celsius
     protected function fahrenheitToCelsius($fahrenheit){
        
        $celsius = round(($fahrenheit - 32) / (9/5));
        $celsius = $celsius . " C";

        return $celsius;
     }

     //Save the temperature in db
     public function saveTemperature(Request $request)
     {
        $user= Auth::user();

        $current_time = date("Y-m-d H:i:s", strtotime('+1 hours'));
        $end_time = date("Y-m-d H:i:s", strtotime('+12 hours'));     
        //save hours
        $hour = new Hour();
        
        $hour->hour_first = $request['all_hours'][0];
        $hour->hour_second = $request['all_hours'][1]; 
        $hour->hour_third = $request['all_hours'][2];
        $hour->hour_fourth = $request['all_hours'][3];
        $hour->hour_fifth = $request['all_hours'][4];
        $hour->hour_sixth = $request['all_hours'][5];
        $hour->hour_seventh = $request['all_hours'][6];
        $hour->hour_eighth = $request['all_hours'][7];
        $hour->hour_ninth = $request['all_hours'][8];
        $hour->hour_tenth = $request['all_hours'][9];
        $hour->hour_eleventh = $request['all_hours'][10];
        $hour->hour_twelfth = $request['all_hours'][11];
        $hour->save();

        $hour_id = $hour->id;

        $temp = new Temperature();

        $current_temp = (int)trim(substr($request['current_temp'], 0 ,2));

        foreach($request['all_temp'] as $key => $value)
        {
           $temp_final[] = (int)trim(substr($request['all_temp'][$key], 0 ,2));
        }
    
        $temp->city_code = (int)$request['city_code'];
        $temp->user_id = $user->id;
        $temp->current_temperature = $current_temp;
        $temp->temp_first = $temp_final[0];
        $temp->temp_second = $temp_final[1]; 
        $temp->temp_third = $temp_final[2];
        $temp->temp_fourth = $temp_final[3];
        $temp->temp_fifth = $temp_final[4];
        $temp->temp_sixth = $temp_final[5];
        $temp->temp_seventh = $temp_final[6];
        $temp->temp_eighth = $temp_final[7];
        $temp->temp_ninth = $temp_final[8];
        $temp->temp_tenth = $temp_final[9];
        $temp->temp_eleventh = $temp_final[10];
        $temp->temp_twelfth = $temp_final[11];
        $temp->hour_id = $hour_id;
        $temp->start_time = $current_time;
        $temp->end_time = $end_time;
        $temp->save(); 

         $response = array(
            'status' => 'success'
        );
        
        return response()->json($response);
     }

     public function show() 
     {
        $cities = City::orderBy('name','asc')->pluck('name', 'city_code')->all();

        return view('find_temperature', compact('cities'));
     }

     public function findTemperature(Request $request)
     {
        $user= Auth::user();

         //napravi provjeru da se vrati temp veca od 0 i manja od 12 sati
         $date_time_from = $request['from_date'] . ' ' . $request['time_from'];
         $date_time_to = $request['to_date'] . ' ' . $request['time_to'];
         $date_time_from = strtotime($date_time_from);
         $date_time_to = strtotime($date_time_to);
         $date_time_from = gmdate("Y-m-d H:i:s", $date_time_from);
         $date_time_to = gmdate("Y-m-d H:i:s", $date_time_to);

         $start_time = \DB::table('temperatures')
         ->select('start_time')
         ->where("user_id", $user->id)
         ->where("city_code", $request['city_code'])
         ->where('start_time', '>=', $date_time_from)
         ->first();

         if (!empty($start_time->start_time))
         {
             $start_time = date("Y-m-d H:i:s", strtotime($start_time->start_time)-3600);
             $date_time_from = $start_time;
         } 

        //check if time date time difference is negative between date time inputs
        $new_date_time = strtotime($date_time_to) - strtotime($date_time_from);
        $input_date_time_diff = floor($new_date_time / 3600);
        

        if ($input_date_time_diff < 0) {

            $response = array(
                'status' => 'success',
                'results' => 'No results'
            );

            return response()->json($response);
        }

        if ($input_date_time_diff >= 0 && $input_date_time_diff < 12){

            $temp = \DB::table('temperatures')
                    ->select('current_temperature', 'temp_first', 'temp_second', 'temp_third', 'temp_fourth', 'temp_fifth', 'temp_sixth', 'temp_seventh', 
                    'temp_eighth', 'temp_ninth', 'temp_tenth', 'temp_eleventh', 'temp_twelfth', 'start_time', 'end_time')
                    ->where("user_id", $user->id)
                    ->where("city_code", $request['city_code'])
                    ->where('start_time', '>=', $date_time_from)
                    ->orderBy('id', 'desc')
                    ->first();
                   
                    if (!isset($temp))
                    {  
                        $response = array(
                            'status' => 'success',
                            'results' => 'No results'
                        );
                        
                        return response()->json($response);
                    }  
                    
            $temperatures_to_show = [$temp->current_temperature, $temp->temp_first ,$temp->temp_second, $temp->temp_third, $temp->temp_fourth,
            $temp->temp_fifth, $temp->temp_sixth, $temp->temp_seventh, $temp->temp_eighth, $temp->temp_ninth, 
            $temp->temp_tenth, $temp->temp_eleventh, $temp->temp_twelfth
            ];
            
            $temperatures_to_show = array_slice($temperatures_to_show, 0, $input_date_time_diff+1);
            
            $response = array(
                'status' => 'success',
                'temperatures' => $temperatures_to_show,
                'results' => 'Temperatures from '. date_format(date_create($request['from_date']),"d.m.Y.") .' '
                .$request['time_from']. ' to ' .date_format(date_create($request['to_date']),"d.m.Y.").' '.$request['time_to']

            );

            return response()->json($response);
        }
        
        else{
            
            $temp = \DB::table('temperatures')
                    ->select('current_temperature', 'temp_first', 'temp_second', 'temp_third', 'temp_fourth', 'temp_fifth', 'temp_sixth', 'temp_seventh', 
                    'temp_eighth', 'temp_ninth', 'temp_tenth', 'temp_eleventh', 'temp_twelfth', 'start_time', 'end_time')
                    ->where("user_id", $user->id)
                    ->where("city_code", $request['city_code'])
                    ->where('start_time', '>=', $date_time_from)
                    ->where('end_time', '<=', $date_time_to)
                    ->get();

                    if (count($temp) == 0)
                    {  
                        $response = array(
                            'status' => 'success',
                            'results' => 'No results'
                        );
                        
                        return response()->json($response);
                    }  
                    
                    $temperatures_to_show = [$temp[0]->current_temperature, $temp[0]->temp_first ,$temp[0]->temp_second, $temp[0]->temp_third, $temp[0]->temp_fourth,
                            $temp[0]->temp_fifth, $temp[0]->temp_sixth, $temp[0]->temp_seventh, $temp[0]->temp_eighth, $temp[0]->temp_ninth, 
                            $temp[0]->temp_tenth, $temp[0]->temp_eleventh, $temp[0]->temp_twelfth
                    ];           
                    
                    if (count($temp) == 1)
                    {  
                        $response = array(
                            'status' => 'success',
                            'temperatures' => $temperatures_to_show,
                            'results' => 'Temperatures from '. date_format(date_create($request['from_date']),"d.m.Y.") .' '
                            .$request['time_from']. ' to ' .date_format(date_create($request['to_date']),"d.m.Y.").' '.$request['time_to']
                        );
                        
                        return response()->json($response);
                    }
                    
                    else { 
                        //check for new added temperatures 
                        $hours_diff = [];

                        for ($i = 0, $j=1; $j<count($temp); $i++, $j++)
                        {
                            $delta_time = strtotime($temp[$j]->end_time) - strtotime($temp[$i]->end_time);
                            $hours = floor($delta_time / 3600);
                            $hours_diff[] = $hours;
                            
                        }

                        $all_zero = true;

                        foreach($hours_diff as $value)
                        if($value != 0)
                        {
                            $all_zero = false;
                            break;
                        }

                        //separate new temperatures and add them to $temperatures_to_show array
                        if (!empty($hours_diff && $all_zero == false)) {

                            $temp_array = [];
                            $temp_array_final = [];

                            for ($i = 1; $i<count($temp); $i++)
                            {
                                $temp_array[$i] = [$temp[$i]->temp_first ,$temp[$i]->temp_second, $temp[$i]->temp_third, $temp[$i]->temp_fourth,
                                $temp[$i]->temp_fifth, $temp[$i]->temp_sixth, $temp[$i]->temp_seventh, $temp[$i]->temp_eighth, $temp[$i]->temp_ninth, 
                                $temp[$i]->temp_tenth, $temp[$i]->temp_eleventh, $temp[$i]->temp_twelfth];
                            }
                            
                            //merge new temperatures with exsistin temperatures
                            for ($i = 0; $i<count($hours_diff); $i++)
                            {                    
                                $finalArray = array_slice($temp_array[$i+1], 12-$hours_diff[$i], 11);
                                $temperatures_to_show = array_merge($temperatures_to_show, $finalArray);
                                
                                if($i==12){
                                    break;
                                }

                                unset($finalArray);                      
                            }   
                    
                        }
                        
                        $response = array(
                            'status' => 'success',
                            'temperatures' => $temperatures_to_show,
                            'results' => 'Temperatures from '. date_format(date_create($request['from_date']),"d.m.Y.") .' '
                            .$request['time_from']. ' to ' .date_format(date_create($request['to_date']),"d.m.Y.").' '.$request['time_to']
                        );
                        
                        return response()->json($response);
                    }

            }     
     }
}
