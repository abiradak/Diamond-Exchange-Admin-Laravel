<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Method extends Model
{
    protected $table = 'methods';

    protected $fillable = [
    	'controller_id' , 'method' , 'route' , 'created_at' , 'updated_at'
    ];

    public function controller() {
	      return $this->belongsTo('App\model\Controllers', 'id', 'controller_id');
	}

	public function action() {
	      return $this->belongsTo('App\model\UserAction', 'action_id', 'id');
	}
}
