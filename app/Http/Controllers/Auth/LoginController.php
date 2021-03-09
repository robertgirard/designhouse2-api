<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Illuminate\Support\Facades\Route;


use Auth;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    public function login( Request $request )
    {
        if (! Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ])) {
            return response()->json([
                'error' => 'invalid_credentials'
            ], 403);
        }

        $user = Auth::user();

        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            return response()->json('email not verified', 403);
        }

        return response()->json(['message' => $user->name . ' successfully Logged In'], 201 );

    }


    public function attemptLogin(Request $request)
    {

         // attempt to issue a token to the user based on the login credentials
       $token = $this->guard()->attempt($this->credentials($request));

        if(! $token){
            return false;
        }

        // Get the authenticated user
        $user = $this->guard()->user();
        //$user = Auth::user();


        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            return false;
        }

        // set the user's token
        $this->guard()->setToken($token);

        return true;
    }

    protected function sendLoginResponse(Request $request)
    {

        $this->clearLoginAttempts($request);

        // get the tokem from the authentication guard (JWT)
        $token = (string)$this->guard()->getToken();

        // extract the expiry date of the token
        $expiration = $this->guard()->getPayload()->get('exp');

        return response()->json([
              'message' => 'User Name ' . Auth::user()->username . ' was successfully logged in',
              'user' => Auth::user(),
              'authenticated' => Auth::guard(),
//            'username' => Auth::user()->username,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ]);
    }


    protected function sendFailedLoginResponse(Request $request)
    {

        $user = $this->guard()->user();

        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "message" => "You need to verify your email account"
            ]], 422);
        }

//        return response()->json([$this->username() => 'Invalid Credentials']);

        throw ValidationException::withMessages([$this->username() => "Invalid Credentials"]);

    }

    public function logout(Request $request)
    {

        Auth::logout();

        return response()->json(['message' => 'Logged out Successfully'], 200);

    }

}
