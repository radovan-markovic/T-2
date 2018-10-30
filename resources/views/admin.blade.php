@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <h1>Admin Panel</h1>

         <table class="table">
            <thead>
                <tr>
                    <th> id</th>
                    <th> Name</th>
                    <th> email </th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td> {{$user->id}} </td>
                    <td> {{$user->name}} </td>
                    <td> {{$user->email}} </td>
                    
                    <td>
                    @if ($user->active_user == 1)
                        {!! Form::open(array('method' => 'patch', 'action' => ['AdminController@activateUser', $user->id])) !!}
                            <div>
                            {!! Form::submit('Deactivate User', ['class' => 'btn btn-danger']) !!}
                            </div>
                        {!! Form::close() !!}
                    @else
                    {!! Form::open(array('method' => 'patch', 'action' => ['AdminController@activateUser', $user->id])) !!}
                            <div>
                            {!! Form::submit('Activate User', ['class' => 'btn btn-success']) !!}
                            </div>
                        {!! Form::close() !!}
                    @endif
                    </td>
                </tr>
                @endforeach
        </tbody>
        </table>
    </div>
    </div>
</div>
@endsection