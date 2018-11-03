<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return view('admin', compact('users'));
    }

    //Update user activity
    public function update($user_id)
    {
        $user = User::find($user_id);
    
        if($user->active_user == true){
            $user->active_user = false;
        }
        else{
            $user->active_user = true;
        }

        $user->save();

        return redirect('/admin');
    }

    public function activateUser($user_id)
    {
        $user = User::find($user_id);
    
        if($user->active_user == true){
            $user->active_user = false;
            
        }
        else{
            $user->active_user = true;
        }

        $user->save();

        return redirect('/admin');
    }

    public function showUserSavedData($user_id)
    {
        $temps = \DB::table('temperatures')
            ->join('cities', 'temperatures.city_code', '=', 'cities.city_code')
            ->select('temperatures.*', 'cities.name')
            ->where('user_id', $user_id)
            ->orderBy('cities.name', 'asc')
            ->get();

            return view('admin_users_data', compact('temps'));
    }
    
}
