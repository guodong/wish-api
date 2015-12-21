<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function login()
    {
        $user = User::whereRaw('tel = ? and password = ?', array(
            Input::get('tel'),
            md5(Input::get('password'))
        ))->first();
        
        if ($user) {
//             if ($user->nickname == '' || $user->schoolId == '' || $user->avatarUrl == ''){
//                 return $this->outputError('请完善用户信息', 2);
//             }
            Session::put('uid', $user->_id);
            $user->school;
            unset($user->schoolId);
	        $user->tags;
            return $this->output($user);
        } else {
            return $this->outputError('tel or psw error', 2);
        }
    }

    public function logout()
    {
        Session::forget('uid');
        return $this->output(new \stdClass());
    }

    public function register()
    {
        if (Input::get('authcode') != Session::get('code')) {
            return $this->outputError("authcode error", 3);
        }
        if (User::where('tel', '=', Input::get('tel'))->first()) {
            return $this->outputError('user exist', 2);
        }
        $data = Input::get();
        $data['password'] = md5(Input::get('password'));
        $user = User::create($data);
        Session::put('uid', $user->_id);
        $user->hxid = uniqid();
        $user->hxpassword = uniqid();
        $user->save();

        function _curl_request($url, $body, $header = array(), $method = "POST")
        {
            array_push($header, 'Accept:application/json');
            array_push($header, 'Content-Type:application/json');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
            switch ($method) {
                case "GET":
                    curl_setopt($ch, CURLOPT_HTTPGET, true);
                    break;
                case "POST":
                    curl_setopt($ch, CURLOPT_POST, true);
                    break;
                case "PUT":
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    break;
                case "DELETE":
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                    break;
            }
            
            curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
            if (isset($body{3}) > 0) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }
            if (count($header) > 0) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            }
            $ret = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);
            if ($err) {
                return $err;
            }
            return $ret;
        }
        
        $formgettoken = "https://a1.easemob.com/tongjo/wishes/token";
        $body = array(
            "grant_type" => "client_credentials",
            "client_id" => "YXA6CE4zcDrBEeWj7nPvppG3zQ",
            "client_secret" => "YXA6M2SaEUshHM5w68Gc08D909fD9TY"
        );
        $patoken = json_encode($body);
        $res = _curl_request($formgettoken, $patoken);
        $tokenResult = array();
        $tokenResult = json_decode($res, true);
        $access_token = $tokenResult['access_token'];
        
        $formauthreg = "https://a1.easemob.com/tongjo/wishes/users";
        $regbody = array(
            "username" => $user->hxid,
            "password" => $user->hxpassword
        );
        $pareg = json_encode($regbody);
        
        $header = array();
        array_push($header, 'Accept:application/json');
        array_push($header, 'Content-Type:application/json');
        array_push($header, $access_token);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $formauthreg);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        if (isset($pareg{2}) > 0) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $pareg);
        }
        if (count($header) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $ret = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            return $this->outputError('error', 4);
        }
        $res = json_decode($ret, true);
        if ($res['entities'] != NULL)
            return $this->output($user);
        return $this->outputError('some error', 4);
    }
}
