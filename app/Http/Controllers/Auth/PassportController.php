<?php

namespace App\Http\Controllers\Auth;

use DB;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use App\Rules\EmailOrZAPhone;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class PassportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Passport Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles api authentication for users for the application
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'refreshToken']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function login(Request $request)
    {
    	
        $validateData = request()->validate([
            'username' => ['required', new EmailOrZAPhone()],
            'password' => 'required'
        ]);

        $request->merge([
            'username'      => $validateData['username'],
            'password'      => $validateData['password'],
            'grant_type'    => 'password',
            'client_id'     => $this->client()->id,
            'client_secret' => $this->client()->secret,
            'scope'         => '*'
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return Route::dispatch($proxy);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function refreshToken(Request $request)
    {
        $request->merge([
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => $this->client()->id,
            'client_secret' => $this->client()->secret,
        ]);

        $proxy = Request::create(
            '/oauth/token',
            'POST'
        );

        return Route::dispatch($proxy);
    }
	
	/**
	 * Logout
	 * @param  Request $request Incoming request
	 * @return object           Reponse
	 */
	public function logout(Request $request)
    {
    	if (!$this->guard()->check()) {
            return response([
                'message' => 'No active user session was found'
            ], 404);
        }
        // api logout
        $request->user('api')->token()->revoke();

        // web logout
        //Auth::guard()->logout();

        //Session::flush();

        //Session::regenerate();

        return response()->json([
            'message' => 'User was logged out'
        ]);
    }

    public function refresh(Request $request)
    {
        return ':)';
    }
    /**
     * Password Grant client object
     * 
     * @return Laravel\Passport\Client
     */
    private function client()
    {
    	return Client::find(2);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('api');
    }
}
