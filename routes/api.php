<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', 'Auth\LoginController2@logout');
    Route::get('/me', 'User\MeController@getMe');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});



//Route::post('login', 'Auth\LoginController@login');
Route::post('login', 'Auth\LoginController2@login');
Route::post('register', 'Auth\RegisterController@register');
Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('verification/resend', 'Auth\VerificationController@resend');
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::put('settings/profile', 'User\SettingsController@updateProfile');
Route::put('settings/password', 'User\SettingsController@updatePassword');


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
