<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class SportType extends Model
{
    protected $table = 'sport_type';

    protected $fillable = [
        'name', 'sport_id' , 'active' ,'delete',
        'created_at' , 'updated_at'
    ];

    public function match() {
	   return $this->hasMany('App\model\Match' , 'sport_type' ,'id');
	}

	public function sport() {
		return $this->hasOne('App\model\Sports' , 'id' , 'sport_id');
	}
    public function competetion() {
        return $this->hasMany('App\model\Competetion' , 'sport_type' , 'id');
    }
}
