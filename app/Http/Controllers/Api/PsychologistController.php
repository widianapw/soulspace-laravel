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
//            sort by distance
            $psychologists = Psychologist::select("name", "phone_number", "address", "latitude", "longitude")
                ->havingRaw("SQRT(POW(69.1 * (latitude - ?), 2) + POW(69.1 * (? - longitude) * COS(latitude / 57.3), 2)) < 5", [$request->latitude, $request->longitude])
                ->get();
            return PsychologistResource::collection($psychologists);
        }else {
            $psychologists = Psychologist::get();
            return PsychologistResource::collection($psychologists);
        }
    }
}
