<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    //protected $primaryKey = 'id';

    protected $table = 'market';

    protected $fillable = [
    	'market_id' , 'event_id' , 'name' , 'result' , 'bet_max' ,
    	'bet_min' , 'commission' , 'declared' , 'completed' ,
    	'locked' , 'active' , 'delete' , 'created_at' , 'updated_at'
    ];

    public function match() {
    	return $this->hasOne('App\model\Match', 'event_id' , 'event_id');
    }
}
