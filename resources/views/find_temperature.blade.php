@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <button class="btn btn-primary float-left"  type="button"onclick="goBack()">Back</button>
            <h3><p class=".text-dark row justify-content-center">Search for saved temperatures</p></h3>       
            <div class="mt-4">
                
                <select  name="cities" id="get_city_code" style="width: auto;">
                        @foreach($cities as $key => $city)
                        <option value="{!! $key !!}">{!! $city!!}</option>
                        @endforeach
                </select>
                <label for="from" style="margin-left:40px">From</label>
                <input id="from_date"  required class="datepicker-input" type="date" data-date-format="dd.mm.yyyy" style="width: auto;" required=''/>
                <select id="time_from">
                    <option value="00:00">00:00</option>
                    <option value="01:00">1:00</option>
                    <option value="02:00">2:00</option>
                    <option value="03:00">3:00</option>
                    <option value="04:00">4:00</option>
                    <option value="05:00">5:00</option>
                    <option value="06:00">6:00</option>
                    <option value="07:00">7:00</option>
                    <option value="08:00">8:00</option>
                    <option value="09:00">9:00</option>
                    <option value="10:00">10:00</option>
                    <option value="11:00">11:00</option>
                    <option value="12:00">12:00</option>
                    <option value="13:00">13:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00">15:00</option>
                    <option value="16:00">16:00</option>
                    <option value="17:00">17:00</option>
                    <option value="18:00">18:00</option>
                    <option value="19:00">19:00</option>
                    <option value="20:00">20:00</option>
                    <option value="21:00">21:00</option>
                    <option value="22:00">22:00</option>
                    <option value="23:00">23:00</option>
                </select>
                <label for="to" style="margin-left:40px">To</label>
                <input id="to_date" required class="datepicker-input" type="date" data-date-format="dd.mm.yyyy" style="width: auto;" required='' />
                <select id="time_to">
                    <option value="00:00">00:00</option>
                    <option value="01:00">1:00</option>
                    <option value="02:00">2:00</option>
                    <option value="03:00">3:00</option>
                    <option value="04:00">4:00</option>
                    <option value="05:00">5:00</option>
                    <option value="06:00">6:00</option>
                    <option value="07:00">7:00</option>
                    <option value="08:00">8:00</option>
                    <option value="09:00">9:00</option>
                    <option value="10:00">10:00</option>
                    <option value="11:00">11:00</option>
                    <option value="12:00">12:00</option>
                    <option value="13:00">13:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00">15:00</option>
                    <option value="16:00">16:00</option>
                    <option value="17:00">17:00</option>
                    <option value="18:00">18:00</option>
                    <option value="19:00">19:00</option>
                    <option value="20:00">20:00</option>
                    <option value="21:00">21:00</option>
                    <option value="22:00">22:00</option>
                    <option value="23:00">23:00</option>
                </select>
                <button id="find_button" class="button btn btn-primary" style="margin-left:40px" >Find</button>

                </div>

             
                <div class="results float-left row justify-content-center mt-4" id="t">
                </div> 
               

                <table id ="finded_temp" class="table table-striped">
                            <thead > 
                            </thead>
                            <tbody>
                                <tr class="temps_per_hour">
                                <tr>
                            </tbody>
                </table>     
                
            </div>

            <div class="mt-4 row justify-content-center" id="chart_div" style="width: 900px; height: 500px"></div>
               
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
 
 
   

<script>
$(document).ready(function(){
   $('.button').click(function(){
        if ($('#from_date').val() == ""){
            alert('Please complete from date field');
        }
        if ($('#to_date').val() == ""){
            alert('Please complete to date field');
        }
    });
});


function goBack() {
    window.history.back();
}

var all_results;

$(document).ready(function(){
        $("#find_button").click(function(){  
        $(".results").empty();                 
        });
    });

$(document).ready(function(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#find_button").click(function(){
            $.ajax({ 
                url: '/temperature/findTemperature',
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,  
                    city_code: $('#get_city_code').val(),
                    from_date: $('#from_date').val(),
                    time_from: $('#time_from').val(),
                    to_date: $('#to_date').val(),
                    time_to: $('#time_to').val()
                    
                },
                dataType: 'JSON',
                success: callbackResults 
            }); 
        });
   }); 

   function callbackResults(data){
         
        $(".temps_per_hour").empty();
        $("#chart_div").empty();
        all_results = data;         
        $(".results").append(data.results); 

        $(function() {
                $.each(data.temperatures, function(i, item) {
                     
                    var $tr = $('.temps_per_hour').append(
                        $('<td>').text(item)
                    )               
                });
            });

            // Load google charts
            google.charts.load('current', {'packages':['line', 'corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var chartDiv = document.getElementById('chart_div');

                var data = new google.visualization.DataTable();
                data.addColumn('string', "Hours");
                data.addColumn('number', "Temperature");

                if (all_results.temperatures !== undefined){
                    
                    all_results.temperatures.forEach(function(element) {
                        data.addRows([['',  element]]);
                    });

                    var materialOptions = {
                        chart: {
                        title: 'Temperatures per hours:'
                        },
                        width: 800,
                        height: 500,
                        series: {
                        // Gives each series an axis name that matches the Y-axis below.
                        0: {axis: 'Temps'}
                        },
                        axes: {
                        // Adds labels to each axis; they don't have to match the axis names.
                        y: {
                            Temps: {label: 'Temps (Celsius)'}
                        }
                        }
                    };

                    function drawMaterialChart() {
                        var materialChart = new google.charts.Line(chartDiv);
                        materialChart.draw(data, materialOptions);
                    }

                    function drawClassicChart() {
                        var classicChart = new google.visualization.LineChart(chartDiv);
                        classicChart.draw(data, classicOptions);
                    }

                    drawMaterialChart();
                    
                }else{

                  
                }      

            }         
                        
   }


</script>
 
@endsection