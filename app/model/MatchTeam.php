<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class MatchTeam extends Model
{
    protected $table = 'match_team';

    protected $fillable = [
    	'event_id' , 'match_id' ,'team_id' , 'created_at' ,
    	'updated_at'
    ];
}
