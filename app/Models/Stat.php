<?php namespace App\Models;

class Stat extends BaseModel{

	protected $table = 'stat';

	protected $fillable = ['place', 'url', 'count'];

}
