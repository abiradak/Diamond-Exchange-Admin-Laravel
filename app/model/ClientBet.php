<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class ClientBet extends Model
{
    protected $table = 'clients_bet_config';
    

    protected $fillable = [
    	'client_id' , 'sport_id' , 'min_bet' , 'max_bet' , 'delay',
    	'created_at' , 'updated_at'
    ];

    public function client() {
    	return $this->belongsTo('App\model\Betusers' , 'id' , 'client_id');
    }
}
