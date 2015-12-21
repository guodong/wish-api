<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Session;
use App\Models\User;

abstract class Controller extends BaseController
{
    
    use DispatchesCommands, ValidatesRequests;
    // 验证用户是否登陆，未登录则exit；若登陆用户和目标用户不一致，为攻击
    protected function auth()
    {
        if (! Session::has('uid')) {
            header('Content-Type: text/json');
            echo json_encode($this->outputError('need logn'));
            exit();
        }else {
            return User::find(Session::get('uid'));
        }
    }

    protected function output($data, $code = 0, $message = "")
    {
        if (! $data) {
            $data = new \stdClass();
        }
        return array(
            "result" => array(
                "code" => $code,
                "message" => $message
            ),
            "data" => $data
        );
    }

    protected function outputError($message = "", $code = 1)
    {
        return array(
            "result" => array(
                "code" => $code,
                "message" => $message
            ),
            "data" => new \stdClass()
        );
    }

    protected function getHxToken()
    {
        $uri = "https://a1.easemob.com/tongjo/wishes/token";
        $data = array(
            "grant_type" => "client_credentials",
            "client_id" => "YXA6CE4zcDrBEeWj7nPvppG3zQ",
            "client_secret" => "YXA6M2SaEUshHM5w68Gc08D909fD9TY"
        );
        $header = array();
        array_push($header, 'Accept:application/json');
        array_push($header, 'Content-Type:application/json');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $return = curl_exec($ch);
        curl_close($ch);
        $ret = json_decode($return);
        return $ret->access_token;
    }
}
