<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Sports extends Model
{
    protected $table = 'sports';

    protected $fillable = [
        'name' , 'active' ,'delete', 'image' , 'primary' ,
        'created_at' , 'updated_at', 'is_sidebar'
    ];
    public function competetion() {
	      return $this->hasMany('App\model\Competetion', 'sport_id', 'id');
	}
	
	public function match() {
	      return $this->hasOne('App\model\Match', 'sport_id' ,'id');
	}

	public function sportType() {
		return $this->hasMany('App\model\SportType', 'sport_id' ,'id');
	}

	public function team() {
		return $this->hasMany('App\model\Team', 'sport_id' , 'id');
	}
}
