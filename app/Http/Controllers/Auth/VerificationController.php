<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Repositories\Contracts\IUser;
use App\Providers\RouteServiceProvider;
//use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{

    public function __construct(IUser $users)
    {
//        $this->middleware('auth');
//        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
        $this->users = $users;
    }

    public function verify(Request $request, User $user)
    {

//        return $request;
        return URL::hasValidSignature($request);

         // check if url is a valid signed url
        if(! URL::hasValidSignature($request))
        {
            return response()->json(["errors" => [
                "message" => "Invalid verification link or signature"
            ]], 422);
        }

        // check if the user has already verified the account
        if($user->hasVerifiedEmail())
        {
            return response()->json(["errors" => [
                "message" => "Email address already verified"
            ]], 422);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'Email successfully verified'], 200);

    }

    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => ['email', 'required']
        ]);

//        $user = User::where('email', $request->email)->first();
        $user = $this->users->findWhereFirst('email', $request->email);

        if(! $user)
        {
            return response()->json([
                "errors" => [
                    "No user could be found with this e-mail address"
                ]
                ], 422);
        }

        if($user->hasVerifiedEmail()){
            return response()->json([
                "errors" =>
                ["Email address has already been verified"
                ]
            ], 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => 'verification link resent']);
    }
}


