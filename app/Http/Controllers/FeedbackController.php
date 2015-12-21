<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Models\Activity;
use Illuminate\Support\Facades\Session;
use App\Models\Comment;
use App\Models\Feedback;
class FeedbackController extends Controller {

	public function store()
	{
	    $fb = Feedback::create(Input::get());
	    return $this->output(new \stdClass());
	}
	
	
}
