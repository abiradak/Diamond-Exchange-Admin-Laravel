<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Controllers extends Model 
{
    protected $table = 'controllers';

    protected $fillable = [
    	'controller' , 'created_at' , 'updated_at'
    ];

    public function method() {
	    return $this->hasMany('App\model\Method', 'controller_id', 'id');
	}

	public function action() {
		return $this->hasMany('App\model\UserAction', 'controller_id', 'id');
	}
}
