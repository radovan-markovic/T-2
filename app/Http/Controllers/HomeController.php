<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\City;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user = Auth::user();
        
        if ($user->isAdmin())
        {
            return redirect('/admin');
        }

        $cities = City::orderBy('name','asc')->pluck('name', 'city_code')->all();

        return view('home', compact('cities'));
    }
}
