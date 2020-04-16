<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name','created_at' , 'updated_at'
    ];

    public function user() {
	    return $this->hasOne('App\model\User' , 'role' ,'id');
	}
}
