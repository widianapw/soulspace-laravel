<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PsychologistResource;
use App\Models\Psychologist;
use Illuminate\Http\Request;

class PsychologistController extends Controller
{
    public function get(Request $request)
    {
        if($request->has("latitude") && $request->has("longitude")) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            // Using Haversine formula to calculate distance
            $psychologists = Psychologist::select("*")
                ->selectRaw(
                    "( 3959 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance",
                    [$latitude, $longitude, $latitude]
                )
//                ->having("distance", "<", 5) // Distance less than 5 miles (or kilometers, depending on your choice)
                ->orderBy("distance")
                ->get();

            return PsychologistResource::collection($psychologists);
        } else {
            $psychologists = Psychologist::get();
            return PsychologistResource::collection($psychologists);
        }
    }
}
