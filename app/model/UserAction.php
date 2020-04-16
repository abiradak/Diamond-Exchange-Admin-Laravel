<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    protected $table = 'user_role_action';

    protected $fillable = [
    	'role_id' , 'action_id' ,'controller_id' , 'created_at' , 'updated_at'

    ];

    public function methods() {
	    return $this->hasMany('App\model\Method' , 'id' ,'action_id');
	}
	public function controller() {
		return $this->hasMany('App\model\Controllers' , 'id' , 'controller_id');
	}

	public function controllers() {
		return $this->hasOne('App\model\Controllers' , 'id' , 'controller_id');
	}
	public function method() {
	    return $this->hasOne('App\model\Method' , 'id' ,'action_id');
	}
}
