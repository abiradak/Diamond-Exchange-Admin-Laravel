<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'match';

    protected $fillable = [
        'name' , 'market_id' , 'event_id',  'sport_id' , 'sport_type' , 'shortname' ,
        'competition_id' , 'complete' , 'inplay' , 'date' , 'active' , 'delete' ,
        'created_at' , 'updated_at'
    ];
    
  public function competetion() {
	   return $this->belongsTo('App\model\Competetion','competition_id');
	}
	public function sporttype() {
	   return $this->belongsTo('App\model\SportType', 'sport_type');
	}
  public function sport() {
     return $this->hasOne('App\model\Sports', 'id' , 'sport_id');
  }
  public function teams() {
    return $this->hasManyThrough(
       'App\model\MatchTeam',
       'App\model\Team',
       'id',
       'match_id',
       'id',
       'id'
    );
  }
}
