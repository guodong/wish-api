<?php namespace App\Models;

class Feedback extends BaseModel{

	protected $table = 'feedback';

	protected $fillable = ['tel', 'content', 'email'];

}
