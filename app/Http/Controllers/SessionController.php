<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class SessionController extends Controller {

public function index()
	{
        if (!Session::has('uid')){
            return array('code'=>1, 'data'=>'session timeout');
        }else{
            return array('code'=>0, 'data'=>User::find(Session::get('uid')));
        }
	}
	
    public function destroy()
    {
        Session::forget('uid');
    }

}
