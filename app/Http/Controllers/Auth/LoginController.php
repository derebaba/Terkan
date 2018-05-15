<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\SendVerificationEmail;
use App\Models\User;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
	}
	
	public function authenticate(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
			if (Auth::user()->verified == 0) {
				Auth::logout();

				$user = User::where('email', $email);
				dispatch(new SendVerificationEmail($user));

				return back()->withInput()->withErrors([
					'Email not verified. Verify your email by clicking the link in your email or continue with Facebook.', 
					'Confirmation email is resent, just in case you did not receive it before.']);
			}
            return redirect()->intended('/');
		}
		return back()->withInput()->withErrors(['Wrong email or password']);
	}
	
	public function showLoginForm()
	{
		if(!session()->has('url.intended'))
		{
			session(['url.intended' => url()->previous()]);
		}
		return view('auth.login');    
	}
}
