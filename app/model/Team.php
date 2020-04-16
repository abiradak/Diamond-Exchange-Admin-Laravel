<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'name' , 'short_name' ,'selection_id', 'image' ,
        'active' ,'delete' , 'sport_id', 'sport_type' ,
        'created_at' , 'updated_at'
    ];

    public function sport() {
        return $this->hasOne('App\model\Sports', 'id' , 'sport_id');
    }
}
