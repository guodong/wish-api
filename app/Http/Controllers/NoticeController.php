<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Models\Activity;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Wish;
use App\Models\Notice;
class NoticeController extends Controller {

	
	public function index() {
	    $this->auth();
	    $user = User::find(Session::get('uid'));
	    foreach ($user->notices as $vv){
	        $vv->wish = Wish::find($vv->wishId);
	        unset($vv->wishId);
	        if(!$vv->wish){
	            continue;
	        }
	        $v = $vv->wish;
	        $creator = User::find($v->creatorId);
	        $creator->school;
            unset($creator->schoolId);
	        $v->creatorUser = $creator;
	        $picker = User::find($v->pickerId);
	        if($picker){
	            $picker->school;
                unset($picker->schoolId);
	            $v->pickerUser = $picker;
	        }
	    }
	    return $this->output(array('total'=>Notice::where('userId', '=', $user->id)->count(), 'notices'=>$user->notices));
	}
	
}
