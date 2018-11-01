@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                
                <div class="card-body">

                    <div class="row justify-content-center">
                        <h3>Select a city to see the current temperature</h3>
                    </div>

                    <div class="row justify-content-center mt-2">
                    
                    <select name="cities" class="form-control" id="get_city_code" style="width: auto;">
                        @foreach($cities as $key => $city)
                        <option value="{!! $key !!}">{!! $city!!}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="row justify-content-center mt-2">

                    <button class="btn btn-success" id="check_temp">Check Temperature</button>
                    </div>

                    <h1>
                        <div class="temperature row justify-content-center mt-4" id="t"></div>
                    </h1>

                    <div id="12-hours-temp" style="margin-top: 40px">
                        <p class="text row justify-content-center" style="display: none;">
                            Tepmeratures in the next the 12 hours for the selected city:
                        </p>
                        <table id ="hours" class="table table-striped">
                            <thead class="temp_hours"> 
                            </thead>
                            <tbody>
                                <tr class="temp_per_hour">
                                <tr>
                            </tbody>
                        </table>
                    </div>                          

                    <div class="row justify-content-center mt-4" >
                        <button class="btn btn-primary float right" id="save_temp" style="display: none;">Save Temperature</button>
                    </div>

                    <button type="button" onclick="window.location='../Temperature/index'">Button</button>
                    
                 
            </div>
        </div>
    </div>
</div>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<!-- provide the csrf token -->
<meta name="csrf-token" content="{{ csrf_token() }}" />



<script>

    var current_temperature;
    var all_temperatures = [];
    var all_hours = [];

    $(document).ready(function(){
        $("#check_temp").click(function(){
            $(".temperature").empty(); 
            $(".text").delay(500).show(0);
            $("#save_temp").delay(800).show(0);          
        });
    });

     $(document).ready(function(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#check_temp").click(function(){
           
            $.ajax({ 
                url: '/temperature/getTemperature',
                type: 'POST',
                data: {_token: CSRF_TOKEN, city_code: $('#get_city_code').val()},
                dataType: 'JSON',
                success: callbackCurrentTemperature 
            }); 
        });
   }); 

   function callbackCurrentTemperature(data){
        current_temperature = data.temp;     
        $(".temperature").append(data.temp);              
   }

    $(document).ready(function(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $("#check_temp").click(function(){  

            $.ajax({
                url: '/temperature/getAllTemperatures',
                type: 'POST',             
                data: {_token: CSRF_TOKEN, city_code: $('#get_city_code').val()},
                dataType: 'JSON',               
                success: callbackAllTemperatures
            }); 
        });
   });

   function callbackAllTemperatures(data){

       response = data.all_temp;
                    $(".temp_hours").empty();
                    $(".temp_per_hour").empty();

                        $(function() {
                            $.each(response, function(i, item) {
                                var $thead = $('.temp_hours').append(
                                    $('<th>').text(item.date_time)
                                );
                                all_hours.push(item.date_time); 
                                var $tr = $('.temp_per_hour').append(
                                    $('<td>').text(item.temperature)
                                )
                                all_temperatures.push(item.temperature);
                            });
                        });
        
   } 

    $(document).ready(function(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#save_temp").click(function(){          
            $.ajax({
               
                url: '/temperature/saveTemperature',
                type: 'POST',
                
                data: {
                    _token: CSRF_TOKEN, 
                    city_code: $('#get_city_code').val(),
                    current_temp: current_temperature,
                    all_temp: all_temperatures,
                    all_hours: all_hours

                    },
                dataType: 'JSON',
                
                success: function (data) {     
                    //alert('Temperature saved!');
                },
                error: function(){
                    //alert('Temperature not saved!');
                }
            }); 
        });
   }); 
   
</script>

@endsection


