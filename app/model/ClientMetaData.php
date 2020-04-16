<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class ClientMetaData extends Model
{
    protected $table = 'clients_metadata';
    

    protected $fillable = [
    	'client_id' , 'role' , 'city' , 'credit_reference' ,
    	'exposure_limit' , 'created_at' , 'updated_at'
    ];

    public function client() {
	    return $this->hasOne('App\model\Betusers' , 'id' ,'client_id');
	}
}
