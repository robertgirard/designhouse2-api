<?php

namespace App\Repositories\Eloquent;

use App\Models\Team;
use App\Repositories\Contracts\ITeam;
use App\Repositories\Eloquent\BaseRepository;

class TeamRepository extends BaseRepository implements ITeam
{

    public function model()
    {
       return Team::class;  //  App\Models\Design
    }

    public function fetchUserTeams()
    {
        return auth()->user()->teams;
    }

}
