<?php

namespace App\model;

// use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\Betusers as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash; 



class Betusers extends Authenticatable {
	use HasApiTokens, Notifiable;

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'clients';

     protected $fillable = [
        'username' , 'mobile' , 'name' , 'password' , 'is_multisign' ,
        'orginal_password' , 'active' , 'parent_id',
        'reference' , 'created_at' , 'updated_at'
    ]; 

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword() {
		return $this->password;
	}

	public function role() {
	    return $this->hasOne('App\model\Roles' , 'id' ,'role');
	}

	public function clientMetaData() {
	    return $this->hasOne('App\model\ClientMetaData' , 'client_id' ,'id');
	}

	public function clientBet() {
		return $this->hasMany('App\model\ClientBet' , 'client_id' , 'id');
	}

	public function clientComission() {
		return $this->hasMany('App\model\ClientComission' , 'client_id' , 'id');
	}
   
}
