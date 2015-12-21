<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Models\Wish;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Notice;
use Carbon\Carbon;

// require_once PATH_BASE.'public/vendor/autoload.php';
// use JPush\Model as M;
// use JPush\JPushClient;
// use JPush\Exception\APIConnectionException;
// use JPush\Exception\APIRequestException;
class WishController extends Controller
{

    public function index()
    {
        if (Input::get('_id')) {
            return $this->output(Wish::find(Input::get('_id')));
        }
        $wishes = Wish::where('isPicked', 0)->orderBy('createdTime', 'desc')->get();
        $ws = [];
        if (Input::get('gender') != 2) {
            foreach ($wishes as $v) {
                if (Input::get('gender') === '0') {
                    if ($v->creatorUser && $v->creatorUser->gender === 0) {
                        $ws[] = $v;
                    }
                } else {
                    if ($v->creatorUser && $v->creatorUser->gender == 1) {
                        $ws[] = $v;
                    }
                }
            }
        } else {
            $ws = $wishes;
        }
        $i = 0;
        if (Input::get('lastid')) {
            foreach ($ws as $v) {
                $i ++;
                if ($v->_id == Input::get('lastid')) {
                    break;
                }
            }
        }
        $out = [];
        $n = 0;
        $t = 10;
        if (count($ws) < ($i + $t)) {
            $t = count($ws) - $i;
        }
        for ($n; $n < $t; $n ++) {
            $out[] = $ws[$i];
            $i ++;
        }
        foreach ($out as $v) {
            unset($v->updatedTime);
            $creator = User::find($v->creatorId);
            if ($creator){
                $creator->school;
                $creator->tags;
            }
            unset($creator->schoolId);
            $v->creatorUser = $creator;
            unset($v->creatorId);
            $picker = User::find($v->pickerId);
            if ($picker) {
                $picker->school;
                unset($picker->schoolId);
                $v->pickerUser = $picker;
                unset($v->pickerId);
            }
        }
        return $this->output(array(
            'total' => Wish::where('isPicked', '=', 0)->count(),
            'wishes' => $out
        ));
        $pagenum = Input::get('page') ? Input::get('page') : 1;
        $limit = Input::get('limit') ? Input::get('limit') : 10;
        $wishes = DB::table('wish')->orderBy('createdTime', 'desc')
            ->where('isPicked', 0)
            ->skip(($pagenum - 1) * $limit)
            ->take(10)
            ->get();
        foreach ($wishes as $v) {
            unset($v->updatedTime);
            $creator = User::find($v->creatorId);
            $creator->school;
            unset($creator->schoolId);
            $creator->tags;
            $v->creatorUser = $creator;
            unset($v->creatorId);
            $picker = User::find($v->pickerId);
            if ($picker) {
                $picker->school;
                unset($picker->schoolId);
                $v->pickerUser = $picker;
                unset($v->pickerId);
            }
        }
        return $this->output(array(
            'total' => Wish::where('isPicked', '=', 0)->count(),
            'wishes' => $wishes
        ));
    }

    public function store()
    {
        $user = $this->auth();
        if ($user->nickname == '' || $user->schoolId == '' || $user->avatarUrl == ''){
            return $this->outputError('请完善用户信息', 3);
        }
        $w = Wish::where('creatorId', $user->_id)->where('createdTime', '>=', Carbon::today())->orderBy('createdTime', 'desc')->count();
        if ($w>=3){
            return $this->outputError('一天只能发3个', 2);
        }
        $data = Input::get();
        
        $data['creatorId'] = Session::get('uid');
        $result = Wish::create($data);
        return $this->output(new \stdClass());
    }


    function pick()
    {
        $user = $this->auth();
        
        if ($user->nickname == '' || $user->schoolId == '' || $user->avatarUrl == ''){
            return $this->outputError('请完善用户信息', 3);
        }
        $w = Wish::where('pickerId', $user->_id)->where('pickedTime', '>=', Carbon::today())->orderBy('createdTime', 'desc')->first();
        if ($w){
            return $this->outputError('一天只能摘一个', 2);
        }
        $wish = Wish::find(Input::get('_id'));
        if($wish->isPicked == 1){
            return $this->outputError('手慢了，心愿已经被别人摘取啦！', 5);
        }
        if ($wish->creatorId == Session::get('uid')) {
            return $this->outputError('cannot pick own wish', 4);
        }
        $wish->pickerId = Session::get('uid');
        $wish->isPicked = 1;
        $wish->pickedTime = date("Y-m-d H:i:s", time());
        $wish->save();
        $notice = Notice::create(array('userId'=>$wish->creatorId, 'wishId'=>$wish->_id, 'title'=>'wishes', 'noticeUrl'=>'', 'content'=>'你的心愿被摘取啦！', 'type'=>1));
        
        $token = $this->getHxToken();
        $uri = "https://a1.easemob.com/tongjo/wishes/messages";
        $data = array(
            'target_type' => 'users',
            'target' => [
                $wish->creatorUser->hxid
            ],
            'msg' => array(
                'type' => 'txt',
                'msg' => '你的心愿被摘取啦！'
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
        return $this->output(new \stdClass());
    }

    public function created()
    {
        $user = $this->auth();
        $wishes = Wish::where('creatorId', '=', Session::get('uid'));
        return $this->output($wishes);
    }

    public function picked()
    {
        $this->auth();
        $wishes = Wish::where('pickerId', '=', Session::get('uid'));
        return $this->output($wishes);
    }

    public function mylist()
    {
        $this->auth();
        $user = User::find(Session::get('uid'));
        if (Input::get('type') == 'picked') {
            $wishes = Wish::where('pickerId', '=', Session::get('uid'))->orderBy('isPicked', 'desc')->orderBy('pickedTime', 'desc')->get();
        } else {
            $wishes = Wish::where('creatorId', '=', Session::get('uid'))->orderBy('isPicked', 'desc')->orderBy('pickedTime', 'desc')->orderBy('createdTime', 'desc')->get();
        }
        foreach ($wishes as $v) {
            unset($v->updatedTime);
            $creator = User::find($v->creatorId);
            $v->creatorUser = $creator;
            if ($creator){
                $creator->school;
            }
            $picker = User::find($v->pickerId);
            if ($picker) {
                $picker->school;
                $v->pickerUser = $picker;
            }
        }
        return $this->output(array(
            'wishList' => $wishes
        ));
    }

    public function update(Request $request)
    {
        $user = $this->auth();
        $wish = Wish::find(Input::get("_id"));
        if (! $wish) {
            return $this->outputError('心愿不存在', 2);
        }
        if ($wish->creatorId != $user->_id){
            return $this->outputError('只有创建者可以修改心愿', 3);
        }
        $wish->update(Input::get());
        if ($request->has('isPicked')) {
            if ($request->input('isPicked') == 0) {
                $wish->pickerId = "";
                $wish->isPicked = 0;
                $wish->save();
            }
        }
        $v = $wish;
        $creator = User::find($v->creatorId);
        $creator->school;
        unset($creator->schoolId);
        $v->creatorUser = $creator;
        $picker = User::find($v->pickerId);
        if ($picker) {
            $picker->school;
            unset($picker->schoolId);
            $v->pickerUser = $picker;
        }
        if (Input::get('isCompleted') == 1){
            $notice = Notice::create(array('userId'=>$wish->pickerId, 'title'=>'wishes', 'wishId'=>$wish->_id, 'noticeUrl'=>'', 'content'=>'你摘的心愿被完成啦！', 'type'=>2));
            
            $token = $this->getHxToken();
            $uri = "https://a1.easemob.com/tongjo/wishes/messages";
            $data = array(
                'target_type' => 'users',
                'target' => [
                    $wish->picker->hxid
                ],
                'msg' => array(
                    'type' => 'txt',
                    'msg' => '你摘的心愿被完成啦！'
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
        }
        return $this->output($v);
    }

    public function delete()
    {
        $wish = Wish::find(Input::get("_id"));
        if (! $wish) {
            return $this->outputError('no wish');
        }
        $wish->delete();
        return $this->output(new \stdClass());
    }
}
