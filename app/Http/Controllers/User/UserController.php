<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\IUser;

class UserController extends Controller
{

    public function __construct(IUser $users)
    {
        $this->users = $users;
    }

    public function index()
    {
        $users = User::all();

        return UserResource::collection($users);
    }
}
