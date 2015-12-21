<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class CodeController extends Controller {

	public function index() {
	    if (Input::get('type') == 'reg'){
	        if (User::where('tel', '=', Input::get('tel'))->first()) {
	            return $this->outputError('手机号已注册', 2);
	        }
	    }else if (Input::get('type') == 'reset'){
	        if (!User::where('tel', '=', Input::get('tel'))->first()) {
	            return $this->outputError('user not exist', 3);
	        }
	    }
	    $code = rand(1000, 9999);
	    //$code = 1234; //for test;
	    Session::put('code', $code);
	    Session::put('tel', Input::get('tel'));
	    
	    $flag = 0;
	    $params='';//要post的数据
	    
	    //以下信息自己填以下
	    $mobile='';//手机号
	    $argv = array(
	        'name'=>'18905195926',     //必填参数。用户账号
	        'pwd'=>'48C5F45AAE76D83585CE22991E9C',     //必填参数。（web平台：基本资料中的接口密码）
	        'content'=>'【wishes】您的验证码是：'.$code,   //必填参数。发送内容（1-500 个汉字）UTF-8编码
	        'mobile'=>Input::get('tel'),   //必填参数。手机号码。多个以英文逗号隔开
	        'stime'=>'',   //可选参数。发送时间，填写时已填写的时间发送，不填时为当前时间发送
	        'sign'=>'',    //必填参数。用户签名。
	        'type'=>'pt',  //必填参数。固定值 pt
	        'extno'=>''    //可选参数，扩展码，用户定义扩展码，只能为数字
	    );
	    foreach ($argv as $key=>$value) {
	        if ($flag!=0) {
	            $params .= "&";
	            $flag = 1;
	        }
	        $params.= $key."="; $params.= urlencode($value);// urlencode($value);
	        $flag = 1;
	    }
	    $url = "http://sms.1xinxi.cn/asmx/smsservice.aspx?".$params; //提交的url地址
	    $con= substr( file_get_contents($url), 0, 1 );  //获取信息发送后的状态
	    
	    if($con == '0'){
	        return $this->output('success');
	    }else{
	        return $this->output('fail '.$con);
	    }
		
	}
	
}
