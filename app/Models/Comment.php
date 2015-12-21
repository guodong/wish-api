<?php namespace App\Models;

class Comment extends BaseModel{

	protected $table = 'comment';

	protected $fillable = ['post_id', 'content', 'activity_id', 'user_id'];

}
