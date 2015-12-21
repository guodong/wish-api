<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class AccesstokenController extends Controller
{

    public function index()
    {
        $user = User::whereRaw('tel = ? and password = ?', array(
            Input::get('tel'),
            md5(Input::get('password'))
        ))->first();
        
        if ($user) {
            $token = uniqid();
            Session::put('token', $token);
            Session::put('uid', $user->id);
            $user->school;
            unset($user->choolId);
            return array('code'=>0, 'data'=>$user);
        } else {
            return array('code'=>1, 'data'=>'tel or psw error');
        }
    }
}
