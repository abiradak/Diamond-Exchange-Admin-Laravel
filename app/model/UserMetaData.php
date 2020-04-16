<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class UserMetaData extends Model
{
    protected $table = 'users_metadata';

    protected $fillable = [
    	'user_id' , 'role' , 'city' , 'credit_reference' , 
    	'exposure_limit' , 'created_at' , 'updated_at'
    ];

    public function user() {
	    return $this->hasOne('App\User' , 'id' ,'user_id');
	}
}
