@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h3><p class=".text-dark row justify-content-center">Search for saved temperatures</p></h3>
            <div class="mt-4">
                
                <select  name="cities" id="get_city_code" style="width: auto;">
                        @foreach($cities as $key => $city)
                        <option value="{!! $key !!}">{!! $city!!}</option>
                        @endforeach
                </select>
                <label for="from" style="margin-left:40px">From</label>
                <input id="from_date"  required class="datepicker-input" type="date" data-date-format="dd.mm.yyyy" style="width: auto;" required=''/>
                <select id="select1">
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
                    <option value="13:00">1:00</option>
                    <option value="14:00">2:00</option>
                    <option value="15:00">3:00</option>
                    <option value="16:00">4:00</option>
                    <option value="17:00">5:00</option>
                    <option value="18:00">6:00</option>
                    <option value="19:00">7:00</option>
                    <option value="20:00">8:00</option>
                    <option value="21:00">9:00</option>
                    <option value="22:00">10:00</option>
                    <option value="23:00">11:00</option>
                </select>
                <label for="to" style="margin-left:40px">To</label>
                <input id="to_date" required class="datepicker-input" type="date" data-date-format="dd.mm.yyyy" style="width: auto;" required='' />
                <select id="select2">
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
                    <option value="13:00">1:00</option>
                    <option value="14:00">2:00</option>
                    <option value="15:00">3:00</option>
                    <option value="16:00">4:00</option>
                    <option value="17:00">5:00</option>
                    <option value="18:00">6:00</option>
                    <option value="19:00">7:00</option>
                    <option value="20:00">8:00</option>
                    <option value="21:00">9:00</option>
                    <option value="22:00">10:00</option>
                    <option value="23:00">11:00</option>
                </select>
                <button class="button btn btn-primary" style="margin-left:40px" >Find</button>

                </div>

                
                
        </div>
               
    </div>
</div>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

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


</script>
 
@endsection