<?php


namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash; 

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;  

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    
    protected $fillable = [
        'username' , 'mobile' , 'name' , 'password' , 'is_multisign' ,
        'orginal_password' , 'role' , 'active' , 'parent_id',
        'reference' , 
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
	public function getAuthPassword()
	{
		return $this->password;
	}

	public function role() {
	    return $this->hasOne('App\model\Roles' , 'id' ,'role');
	}

	public function userMetaData() {
	    return $this->hasOne('App\model\UserMetaData' , 'user_id' ,'id');
	}

	public function userBet() {
		return $this->hasMany('App\model\UserBet' , 'user_id' , 'id');
	}

	public function userComission() {
		return $this->hasMany('App\model\UserComission' , 'user_id' , 'id');
	}
	public function userPartnership() {
		return $this->hasMany('App\model\UserPartneShip' , 'user_id' , 'id');
	}
}
