<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

use Auth;

class MeController extends Controller

{

    public function __construct(){
        $this->middleware('auth:sanctum')->only('getMe');
    }

    public function getMe()
    {
        if(auth()->check()){
 //           $user = Auth::user();
            $user = auth()->user();
            return new UserResource($user);

        }
        return response()->json(null, 401);
    }
}
