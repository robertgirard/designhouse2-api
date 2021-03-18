<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use App\Services\Gravatar;
use App\Services\User\Authentication;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;

class LoginController2 extends Controller
{

    use AuthenticatesUsers;

    public function register( Request $request)
    {

    }


    public function login( Request $request )
    {
        $authentication = new Authentication( $request->all() );
        return $authentication->authenticateRequest();
    }

    public function getLoggedInUser(Request $request){
        //return response(auth()->guard()->user());
        return response()->json(auth()->user());
    }


    public function logout(Request $request)
    {

//        dd($request);
//        Auth::logout();
//          auth()->guard()->logout();
        Auth::guard('web')->logout();

        return response()->json(['message' => 'Logged out Successfully'], 200);

    }

}
