<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class MeController extends Controller
{

    public function getMe()
    {
        return auth()->user();

        if(auth()->check()){
            $user = auth()->user();
            return new UserResource($user);
//            return response()->json($user);
//            return new UserResource($user);
        }
        return response()->json(null, 401);
    }
}
