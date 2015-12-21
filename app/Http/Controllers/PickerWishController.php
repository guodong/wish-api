<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Models\Activity;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Models\Wish;
class PickerWishController extends Controller {

    public function index($creator_id)
    {
		return array('code'=>0, 'data'=>Wish::orderBy('picker_id', '=', $creator_id)->get());
    }
  
}
