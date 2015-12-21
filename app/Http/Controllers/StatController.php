<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Stat;

class StatController extends Controller {

	public function index() {
	    $stat = Stat::where('place', Input::get('place'))->first();
	    if($stat){
	        $stat->count++;
	        $stat->save();
	    }else{
	        Stat::create(['place'=>Input::get('place'), 'url'=>Input::get('url'), 'count'=>1]);
	    }
	    $url = Input::get('url');
	    return view('stat', ['url' => $url]);
	}
}
