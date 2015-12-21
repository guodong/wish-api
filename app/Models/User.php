<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['realname', 'email', 'tel', 'password', 'nickname', 'schoolId', 'gender', 'hxid', 'hxpassword'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'createdTime', 'updatedTime'];
	
	public function school()
	{
	    return $this->belongsTo('App\Models\School', 'schoolId');
	}
	
	public function notices()
	{
	    return $this->hasMany('App\Models\Notice', 'userId')->orderBy('createdTime', 'desc');
	}
	
	public function tags()
	{
	    return $this->hasMany('App\Models\Tag', 'userId');
	}
	
}
