<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $table = 'action';

    protected $fillable = [
    	'controller', 'method' , 'request_type' , 'created_at' , 'updated_at'
    ];
    public function action() {
	    return $this->belongsToMany('App\model\UserAction' , 'action_id' ,'id');
	}
}
