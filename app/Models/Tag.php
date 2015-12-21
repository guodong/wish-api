<?php namespace App\Models;

class Tag extends BaseModel{

	protected $table = 'tag';

	protected $fillable = ['name', 'userId'];
	
	protected $hidden = ['userId'];
	
	public $timestamps = false;
	
	public function user()
	{
	    return $this->belongsTo('App\Models\User', 'userId');
	}

}
