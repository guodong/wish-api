<?php namespace App\Models;

class Wish extends BaseModel{

	protected $table = 'wish';

	protected $fillable = ['creatorId', 'pickerId', 'content', 'isCompleted', 'coordinates', 'backgroundColor'];
	protected $hidden = ['updatedTime'];
	
	public function category()
	{
	    return $this->belongsTo('App\Models\Category');
	}
	
	public function creatorUser()
	{
	    return $this->belongsTo('App\Models\User', 'creatorId');
	}
	
	public function pickerUser()
	{
	    return $this->belongsTo('App\Models\User', 'pickerId');
	}

}
