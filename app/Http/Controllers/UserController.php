<?php namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Rhumsaa\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class UserController extends Controller {

	public function index()
	{
	    
	    if(Input::get('_id')){
	        return $this->show(Input::get('_id'));
	    }
	    if (Input::get('hxid')){
	        $user = User::where('hxid', Input::get('hxid'))->first();
	        $user->school;
	        unset($user->schoolId);
	        $user->tags;
	        return $this->output($user);
	    }
	    
		return $this->output(User::all());
	}
	
	public function byhxids()
	{
	    $str = Input::get('hxids');
	    $str = rtrim($str, ','); 
	    $hxids = explode(',', $str);
	    if (count($hxids)==0){
	        return $this->outputError('no hxids');
	    }
	    $users = User::whereIn('hxid', $hxids)->get();
	    foreach ($users as $v){
	        $v->school;
	        $v->tags;
	    }
	    return $this->output(array('userList'=>$users));
	}
	
	public function show($id)
	{
	    $user = User::find($id);
	    if (Input::get('ext_field')) {
    	    $arr = explode(',', Input::get('ext_field'));
    	    foreach ($arr as $v){
    	        $user->{$v};
    	    }
	    }
	    $user->tags;
	    return $this->output($user);
	}
	
	public function edit()
	{
	    $this->auth();
	    $user = User::find(Session::get('uid'));
	    $data = Input::get();
	    if(Input::get('nickname')){
	        $user->nickname = Input::get('nickname');
	    }
	    if(Input::get('schoolId')){
	        $user->schoolId = Input::get('schoolId');
	    }
	    $user->save();
	    $user->school;
	    unset($user->schoolId);
	    return $this->output($user);
	}
	
	public function avatar()
	{
	    $this->auth();
	    $user = User::find(Session::get('uid'));
	    $fn = Uuid::uuid4();
	    $result = move_uploaded_file($_FILES["image"]["tmp_name"], PATH_BASE.'public/avatar/'.$fn.'.jpg');
	    if(!$result){
	        return  $this->outputError('save img error');
	    }
	    $user->avatarUrl = 'http://api.wish.tongjo.com/avatar/'.$fn.'.jpg';
	    $user->save();
	    return $this->output(array('avatarUrl'=>$user->avatarUrl));
	}
	
	public function profile()
	{
	    $this->auth();
	    $user = User::find(Session::get('uid'));
	    $user->nickname = Input::get('nickname')?Input::get('nickname'):'心愿采集者';
	    $user->schoolId = Input::get('schoolId');
	    if(Input::get('gender'))
	        $user->gender = Input::get('gender');
	    $user->save();
	    $user->school;
	    unset($user->schoolId);
	    return $this->output($user);
	}
	
	public function findpsw()
	{
	    if (Input::get('authcode') != Session::get('code')){
	        return $this->outputError("authcode error", 2);
	    }
	    $user = User::where('tel', '=', Session::get('tel'))->first();
	    if($user){
	        $user->password = md5(Input::get('password'));
	        $user->save();
	        return $this->output(new \stdClass());
	    }else{
	        return $this->outputError('用户不存在');
	    }
	}
	
	public function resetpsw()
	{
	    $this->auth();
	    $user = DB::table('user')->where('_id', Session::get('uid'))->first();
	             if ($user->password != md5(Input::get('pwdOld'))) {
	                 return $this->outputError('pwdOld error', 3);
	             }
	    DB::table('user')->where('_id', Session::get('uid'))->update([
	        'password' => md5(Input::get('pwdNew'))
	    ]);
	    return $this->output(new \stdClass());
	}

}
