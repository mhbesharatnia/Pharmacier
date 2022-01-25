<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    //Default location is Mofid Securities = 35.76004078688983, 51.416459848639526
    //           34.85708268350487, 51.418594081857755
    //           35.69566660538688, 52.73146021296373
    public function findNearest($lat = 35.76004078688983, $lon = 51.416459848639526)
    {
        //find in ~100KM square (in Iran)
        $pharmacies = Pharmacy::where('lat', '>', $lat - 0.5)
            ->where('lat', '<', $lat + 0.5)
            ->where('lon', '>', $lon - 0.5)
            ->where('lon', '<', $lon + 0.5)->get();
        if ($pharmacies) {
            $count = count($pharmacies);
            if ($count > 10) {
                $list = [];
                foreach ($pharmacies as $pharmacy) {
                    $list[] = ['dist' => PharmacyController::distanceCalc($pharmacy->lat, $pharmacy->lon, $lat, $lon), "pharmacy" =>$pharmacy];
                }
                usort($list, function ($a, $b) {
                    
                    return $b["dist"] - $a["dist"] > 0 ? -1 : 1;
                });
                $return = [];
                for ($i = 0; $i < 10; $i++) {
                    $return[] = $list[$i]["pharmacy"];
                }
            } else {
                $return = $pharmacies;
            }
            return response(["status" => true, "list" => $return], 200);
        }
        return response(["status" => false, "errorNumber" => 404, "errorMessage" => "not found"], 404);
    }

    private static function distanceCalc(float $p1lat, float $p1lon, float $p2lat, float $p2lon)
    {
        $a = $p1lat - $p2lat;
        $b = $p1lon - $p2lon;
        return (float) ($a * $a) + ($b * $b);
    }
}

function cmp($a, $b)
{
    return $a > $b ? true : false;
}
