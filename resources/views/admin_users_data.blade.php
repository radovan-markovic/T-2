@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Admin Panel</h1>

         <table class="table">
            <thead>
                <tr>
                    <th>City name</th>
                    <th>Temperatures</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Created:</th>
                </tr>
            </thead>
            <tbody>
                @foreach($temps as $temp)
                <tr>
                   <td>{{$temp->name}}</td>
                   <td>{{$temp->temp_first}}</td>
                   <td>{{$temp->temp_second}}</td>
                   <td>{{$temp->temp_fourth}}</td>
                   <td>{{$temp->temp_fifth}}</td>
                   <td>{{$temp->temp_sixth}}</td>
                   <td>{{$temp->temp_seventh}}</td>
                   <td>{{$temp->temp_eighth}}</td>
                   <td>{{$temp->temp_ninth}}</td>
                   <td>{{$temp->temp_tenth}}</td>
                   <td>{{$temp->temp_eleventh}}</td>
                   <td>{{$temp->temp_twelfth}}</td>
                   <td>{{$temp->start_time}}</td>                  
                </tr>
                @endforeach
        </tbody>
        </table>
        <button class="btn btn-primary float-left"  type="button"onclick="goBack()">Back</button>
    </div>
    </div>
</div>

<script>
function goBack() {
    window.history.back();
}
</script>
@endsection