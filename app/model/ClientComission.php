<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class ClientComission extends Model
{
    protected $table = 'clients_commission';
    

    protected $fillable = [ 
    	'client_id' , 'sport_id' , 'commission' , 'created_at' ,
    	'updated_at'
    ];

    public function client() {
    	return $this->belongsTo('App\model\Betusers' , 'id' , 'client_id');
    }
}
