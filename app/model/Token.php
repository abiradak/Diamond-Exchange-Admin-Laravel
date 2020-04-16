<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
	protected $primaryKey = 'id';
    protected $table = 'oauth_access_tokens';

    protected $fillable = [
    	'user_ip', 'user_agent'
    ];

}
