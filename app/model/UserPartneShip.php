<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class UserPartneShip extends Model
{
    protected $table = 'user_partnaship';

    protected $fillable = [
    	'user_id' , 'sport_id' , 'partnaship' , 
    	'created_at' , 'updated_at'
    ];
    
    public function user() {
    	return $this->belongsTo('App\User' , 'id' , 'user_id');
    }
}
