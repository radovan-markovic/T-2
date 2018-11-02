<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Socialite;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

     /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function authenticated(Request $request, $user)
    {
        
        if(!$user['active_user'])
            {
                Auth::logout();
                return redirect('/login');
            }
        
        if ($user->isAdmin()) 
        {
            return redirect('/admin');
        }
    
        return redirect('/home');
    }

   
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $authUser = $this->findOrCreateUser($user, $provider);

        if(!$authUser->active_user)
            {
                Auth::logout();
                return redirect('/login');
            }

        Auth::login($authUser, false);
        return redirect('/home');
    }

    public function findOrCreateUser($user, $provider)
    {          
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser)
        {
            return $authUser;
        }
        return User::create([
            'name'          => $user->name,
            'email'         => $user->email,
            'provider'      => strtoupper($provider),
            'provider_id'   => $user->id

        ]);
    }
}
