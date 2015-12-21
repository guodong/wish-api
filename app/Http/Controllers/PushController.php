<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Models\Activity;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Wish;
use App\Models\Notice;
require_once PATH_BASE . 'public/vendor/autoload.php';
use JPush\Model as M;
use JPush\JPushClient;
use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

class PushController extends Controller
{

    public function test()
    {
        $users = User::all();
        $ids = [];
        $notice;
        if (Input::get('type') === 0){
            foreach ($users as $u){
                if ($u->hxid && $u->gender == 0){
                    $ids[] = $u->hxid;
                    $notice = Notice::create(array('userId'=>$u->_id, 'title'=>Input::get('title')?Input::get('title'):'', 'content'=>Input::get('msg')?Input::get('msg'):'', 'wishId'=>'', 'noticeUrl'=>Input::get('url')?Input::get('url'):'', 'type'=>0));
                }
            }
        }elseif (Input::get('type') === 1){
            foreach ($users as $u){
                if ($u->hxid && $u->gender == 1){
                    $ids[] = $u->hxid;
                    $notice = Notice::create(array('userId'=>$u->_id, 'title'=>Input::get('title')?Input::get('title'):'', 'content'=>Input::get('msg')?Input::get('msg'):'', 'wishId'=>'', 'noticeUrl'=>Input::get('url')?Input::get('url'):'', 'type'=>0));
                }
            }
        }else{
            foreach ($users as $u){
                if ($u->hxid){
                    $ids[] = $u->hxid;
                    $notice = Notice::create(array('userId'=>$u->_id, 'title'=>Input::get('title')?Input::get('title'):'', 'content'=>Input::get('msg')?Input::get('msg'):'', 'wishId'=>'', 'noticeUrl'=>Input::get('url')?Input::get('url'):'', 'type'=>0));
                }
            }
        }
        
        $token = $this->getHxToken();
        $uri = "https://a1.easemob.com/tongjo/wishes/messages";
        $data = array(
            'target_type' => 'users',
            'target' => $ids,
            'msg' => array(
                'type' => 'txt',
                'msg' => Input::get('msg')?Input::get('msg'):'你的心愿被摘取啦！'
            ),
            'ext' => $notice->toArray()
        );
        $header = array();
        array_push($header, 'Accept:application/json');
        array_push($header, 'Content-Type:application/json');
        array_push($header, 'Authorization: Bearer '.$token);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $return = curl_exec($ch);
        curl_close($ch);
        print_r($return);
    }

}
