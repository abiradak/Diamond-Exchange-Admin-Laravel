<?php

namespace App\model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Competetion extends Model
{
    protected $table = 'competition';

    protected $fillable = [
        'sport_id' , 'event_id' , 'name' , 'active', 'image' ,
        'sport_type' , 'created_at' ,'updated_at', 'delete'
    ];

    public function sports() {
	   return $this->belongsTo('App\model\Sports' , 'sport_id' ,'id');
	}
    public function sporttype() {
        return $this->hasOne('App\model\SportType' , 'id' , 'sport_type');
    }
    public function match() {
	   return $this->hasMany('App\model\Match', 'competition_id', 'id');
	}
}
