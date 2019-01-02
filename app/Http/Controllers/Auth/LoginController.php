<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Repository\RegistrationRepo;

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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $registrationRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->registrationRepo = new RegistrationRepo();
    }

    /* Function to handle the service provider type and redirect to its function */
    public function redirectToProvider($id)
    {
        if ($id == 1)
            return Socialite::driver('facebook')->redirect();
        elseif ($id == 2)
            return Socialite::driver('google')->redirect();
        elseif ($id == 3)
            return Socialite::driver('twitter')->redirect();
    }

    /* Handling facebook service login if exist and create new user if not exist */
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();
        $FacebookUser = $this->registrationRepo->findOrCreateUserByProvider($user, 'FACEBOOK');
        if (isset($FacebookUser['validator']))
        {
            if ($FacebookUser['validator'] == 'notVerify')
            {
                dd("You need to verify your account");
            }
            elseif ($FacebookUser['validator'] == 'No Email')
            {
                dd("You tried to login with facebook without entering your email. If you want to login you must create an account.");
            }
        }
        elseif (isset($FacebookUser['errors']))
        {
            dd($FacebookUser['errors']);
        }
        elseif (isset($FacebookUser['success']))
        {
            Auth::login($FacebookUser['success'], true);
            return redirect()->route('home');
        }
    }

    /* Handling google service login if exist and create new user if not exist */
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        $GoogleUser = $this->registrationRepo->findOrCreateUserByProvider($user, 'GOOGLE');

        if (isset($GoogleUser['validator']))
        {
            if ($GoogleUser['validator'] == 'notVerify')
            {
                dd("You need to verify your account");
            }
            elseif ($GoogleUser['validator'] == 'No Email')
            {
                dd("You tried to login with google without entering your email. If you want to login you must create an account.");
            }
        }
        elseif (isset($GoogleUser['errors']))
        {
            dd($GoogleUser['errors']);
        }
        elseif (isset($GoogleUser['success']))
        {
            Auth::login($GoogleUser['success'], true);
            return redirect()->route('home');
        }
    }

    /* Handling twitter service login if exist and create new user if not exist */
    public function handleTwitterCallback()
    {
        $user = Socialite::driver('twitter')->user();
        $TwitterUser = $this->registrationRepo->findOrCreateUserByProvider($user, 'TWITTER');

        if (isset($TwitterUser['validator']))
        {
            if ($TwitterUser['validator'] == 'notVerify')
            {
                dd("You need to verify your account");
            }
            elseif ($TwitterUser['validator'] == 'No Email')
            {
                dd("You tried to login with twitter without entering your email. If you want to login you must create an account.");
            }
        }
        elseif (isset($TwitterUser['errors']))
        {
            dd($TwitterUser['errors']);
        }
        elseif (isset($TwitterUser['success']))
        {
            Auth::login($TwitterUser['success'], true);
            return redirect()->route('home');
        }
    }
}
