<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
    
}
