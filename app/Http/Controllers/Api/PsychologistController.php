<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PsychologistResource;
use App\Models\Psychologist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function getRoute(Request $request)
    {
        $fromLatitude = $request->fromLatitude;
        $fromLongitude = $request->fromLongitude;
        $toLatitude = $request->toLatitude;
        $toLongitude = $request->toLongitude;

//        https://api.openrouteservice.org/v2/directions/driving-car?api_key=5b3ce3597851110001cf6248df1f9528a493402ca0ff8c14c1e31729&start=8.681495,49.41461&end=8.687872,49.420318

        $apiKey = "5b3ce3597851110001cf6248df1f9528a493402ca0ff8c14c1e31729";
        $baseUrl = "https://api.openrouteservice.org/v2/directions/driving-car";

        $url = $baseUrl . "?api_key=" . $apiKey . "&start=" . $fromLongitude . "," . $fromLatitude . "&end=" . $toLongitude . "," . $toLatitude;

        $response = Http::get($url);

        $json = $response->json();
        $coords = $json["features"][0]["geometry"]["coordinates"];
        $formattedCoords = array_map(function($coord) {
            return [
                'lat' => $coord[1],  // Nilai kedua sebagai latitude
                'lng' => $coord[0]  // Nilai pertama sebagai longitude
            ];
        }, $coords);
        return $formattedCoords;
    }
}
