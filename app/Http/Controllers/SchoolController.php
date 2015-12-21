<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Models\Activity;
use App\Models\School;

class SchoolController extends Controller
{

    public function index()
    {
        if (Input::get('coordinates')) {
            $cd = Input::get('coordinates');
            $_cd = explode(',', $cd);
            $wd = $_cd[0];
            $jd = $_cd[1];
            $schools = School::all();
            $ret = array();
            $radius = Input::get('radius') ? Input::get('radius') : 30000;
            foreach ($schools as $v) {
                $tmp = explode(',', $v->coordinates);
                $lat1 = $tmp[0];
                $lng1 = $tmp[1];
                $dst = $this->getDistance($lat1, $lng1, $wd, $jd);
                if ($dst < $radius) {
                    $ret[floor($dst)] = $v;
                }
                ksort($ret);
                $out = [];
                foreach ($ret as $v){
                    $out[] = $v;
                }
            }
            
            return $this->output(array('schoolList'=>$out));
        } else {
            return $this->output(array('schoolList'=>School::orderBy('ord', 'desc')->limit(4)->get()));
        }
    }

    private function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; // approximate radius of earth in meters
        
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        
        return round($calculatedDistance);
    }
}
