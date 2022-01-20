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
        $pharmacy = Pharmacy::where('lat', '>', $lat - 0.5)
            ->where('lat', '<', $lat + 0.5)
            ->where('lon', '>', $lon + 0.5)
            ->where('lon', '<', $lon + 0.5)->get();
        //TODO: sort by distance and limit to 10 selection;
        if ($pharmacy) {
            return response(["status" => true, "list" => $pharmacy], 200);
        }
        return response(["status" => false, "errorNumber"=>404, "errorMessage"=>"not found"], 404);
    }
}
