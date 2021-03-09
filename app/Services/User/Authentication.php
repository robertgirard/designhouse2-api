<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\User\ManageSocialUser;
//use App\Services\Gravatar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Auth;
use Socialite;

class Authentication
{
    private $data;

    public function __construct( $requestData )
    {
        $this->data = $requestData;
    }

    public function authenticateRequest()
    {
        $authMethod = $this->determineAuthMethod();

        switch( $authMethod ){
            case 'social':
                return $this->socialAuthenticate();
            break;
            case 'mobile':
                return $this->mobileAuthenticate();
            break;
            case 'sanctum':
                return $this->sanctumAuthenticate();
            break;
        }
    }

    private function determineAuthMethod()
    {
        if( isset( $this->data['social'] ) && $this->data['social'] ){
            return 'social';
        }else if( isset( $this->data['device_name'] ) ){
            return 'mobile';
        }else{
            return 'sanctum';
        }
    }

    private function socialAuthenticate()
    {
/*
        if( isset( $this->data['redirect_uri'] ) ){
            $provider = $this->data['provider'];
            $redirectURI = $this->data['redirect_uri'];
            $state = $this->data['state'];

            config()->set( "services.".$provider.".redirect", $redirectURI );
        }

        $driver = Socialite::driver( $provider );
        $driver->stateless();

        $profile = $driver->user();
        $mobile = $this->data['mobile'];

        $manageSocialUser = new ManageSocialUser( $provider, $profile );
        $socialUser = $manageSocialUser->authenticate();

        if( $socialUser ){
            if( $mobile ){
                return [
                    'token' => $socialUser->createToken( $this->data['device_name'] )->plainTextToken
                ];
            }else{
                return response()->json( $socialUser );
            }
        }else{
            return response()->json(
                [
                    'error' => 'Authentication failed! You have an account with this email address, please try logging in with your email and password!'
                ],
                403
            );
        }

        return response()->json( '', 204 );
*/
    }

    private function mobileAuthenticate()
    {
/*
        $validator = Validator::make( $this->data, [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        if( !$validator->fails() ){
            // Grab the user that matches the email and check to see if the
            // passwords match.
            $user = User::where('email', $this->data['email'])->first();

            // If there is no user, or the password is incorrect, return a 403 error.
            if (! $user || ! Hash::check($this->data['password'], $user->password)) {
                return response()->json([
                    'error' => 'invalid_credentials'
                ], 403);
            }

            // Return the token for the user to the mobile app.
            return [
                'token' => $user->createToken( $this->data['device_name'] )->plainTextToken
            ];
        }

        return response()->json([
            'error' => 'invalid_credentials',
            403
        ]);
*/
    }

    private function sanctumAuthenticate()
    {

        $validator = Validator::make( $this->data, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if( $validator->fails() ){
            return response()->json([
                'error' => 'invalid_credentials',
                403
            ]);
        }

        if (! Auth::attempt([
            'email' => $this->data['email'],
            'password' => $this->data['password']
        ])) {
            return response()->json([
                'error' => 'invalid_credentials'
            ], 403);
        }

        $user = Auth::user();

        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            return response()->json('email not verified', 403);
        }

        return response()->json('Successful login', 200 );

    }
}
