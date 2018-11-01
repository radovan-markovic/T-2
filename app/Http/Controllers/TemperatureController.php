<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\City;
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
         
       /* $jsonurl = "http://dataservice.accuweather.com/forecasts/v1/hourly/12hour/".$request['city_code']."?apikey=cAzrpFM0nd2RWIj9tjxpdvarhb3X5452";
        $json = file_get_contents($jsonurl);
        $weather = json_decode($json);

        foreach($weather as $key=> $value){
            
            $temperatures[$key]['date_time'] = substr($weather[$key]->DateTime, 11, 5);
            $temperatures[$key]['temperature'] = $this->fahrenheitToCelsius($weather[$key]->Temperature->Value);

        }*/

        $temperatures[0]['date_time'] = '22:00';
        $temperatures[0]['temperature'] = '10 C';
        $temperatures[1]['date_time'] = '23:00';
        $temperatures[1]['temperature'] = '15 C';
        $temperatures[2]['date_time'] = '00:00';
        $temperatures[2]['temperature'] = '16 C';
        $temperatures[3]['date_time'] = '22:00';
        $temperatures[3]['temperature'] = '19 C';

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

         $response = array(
            'status' => 'success'
        );
        
        return response()->json($response);
     }
}
