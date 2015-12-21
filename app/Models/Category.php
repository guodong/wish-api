<?php namespace App\Models;

class Category extends BaseModel{

	protected $table = 'category';

	protected $fillable = ['name', 'fid'];
	
	public function activities()
	{
	    return $this->hasMany('App\Models\Activity');
	}
	
	public function children()
	{
	    return $this->hasMany('App\Models\Category', 'fid');
	}

}
