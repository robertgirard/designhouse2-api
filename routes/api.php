<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', 'Auth\LoginController2@logout');
    Route::get('me', 'User\MeController@getMe');

    // Upload Designs
    Route::post('designs', 'Designs\UploadController@upload');
    Route::put('designs/{id}', 'Designs\DesignController@update');
    Route::delete('designs/{id}', 'Designs\DesignController@destroy');
});

Route::get('/user', function (Request $request) {

    //return response("post man still sucks");
    return $request->user();
});

//Route::get('me', 'User\MeController@getMe');

//  Public Routes

//Route::post('login', 'Auth\LoginController@login');
Route::post('login', 'Auth\LoginController2@login');
Route::get('getUser', 'Auth\LoginController2@getLoggedInUser');
Route::post('register', 'Auth\RegisterController@register');
Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('verification/resend', 'Auth\VerificationController@resend');
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::put('settings/profile', 'User\SettingsController@updateProfile');
Route::put('settings/password', 'User\SettingsController@updatePassword');

Route::get('designs', 'Designs\DesignController@index');
Route::get('designs/{id}', 'Designs\DesignController@findDesign');
Route::get('users', 'User\UserController@index');

// register
/*
Route::get('register', function(Request $request){
    $user = User::create([
        'username' => $request->username,
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password)
    ]);

    return $user;
});
*/
// login
/*
Route::post('login', function(Request $request){
    $credentials = $request->only('email', 'password');

    if(! auth()->attempt($credentials)){
        throw ValidationException::withMessages([
            'email' => 'Invalid credentials'
        ]);
    }

    $request->session()->regenerate();

    return response()->json(null, 201);
});
*/
// logout

/*
Route::post('logout', function(Request $request){
    auth()->guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return response()->json(null, 200);
});
*/
