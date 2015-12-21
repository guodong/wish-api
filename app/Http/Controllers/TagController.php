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
use App\Models\Tag;

// require_once PATH_BASE.'public/vendor/autoload.php';
// use JPush\Model as M;
// use JPush\JPushClient;
// use JPush\Exception\APIConnectionException;
// use JPush\Exception\APIRequestException;
class TagController extends Controller
{

    public function create()
    {
        $user = $this->auth();
        $data = Input::get();
        $data['userId'] = $user->_id;
        $result = Tag::create($data);
        return $this->output($result->_id);
    }


    public function delete()
    {
        $user = $this->auth();
        
        $tag = Tag::find(Input::get('tagId'));
        $tag->delete();
        return $this->output(new \stdClass());
    }
    
    public function index()
    {
        $tags = ['学生党', '萌妹子', 'IT大神', '男神', '女神', '吃货', '萝莉', '御姐', '丑逼', '学霸', '麦霸', '学渣'];
        return $this->output($tags);
    }
}
