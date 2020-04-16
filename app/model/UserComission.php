<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class UserComission extends Model
{
    protected $table = 'users_commission';

    protected $fillable = [
    	'user_id' , 'sport_id'  , 'commission' ,
    	'created_at' , 'updated_at' 
    ];
    public function user() {
    	return $this->belongsTo('App\User' , 'id' , 'user_id');
    }
}
