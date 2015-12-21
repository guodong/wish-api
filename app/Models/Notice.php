<?php namespace App\Models;

class Notice extends BaseModel{

	protected $table = 'notice';
	protected $fillable = ['userId', 'wishId', 'content', 'type', 'noticeUrl', 'title'];

	protected $hidden = ['updatedTime'];
}
