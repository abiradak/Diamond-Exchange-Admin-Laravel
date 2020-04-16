<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class UserBet extends Model
{
    protected $table = 'users_bet_config';

    protected $fillable = [
    	'user_id' , 'sport_id' , 'min_bet' ,
    	'max_bet' , 'delay' , 'created_at' ,
    	'updated_at' 
    ]; 
    public function user() {
    	return $this->belongsTo('App\User' , 'id' , 'user_id');
    }
}
