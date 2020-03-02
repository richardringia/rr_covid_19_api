<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\VirusData;
use App\Models\VirusDataType;
use Illuminate\Http\Request;

class VirusDataController extends Controller
{
    public function all(Request $request) {

        //TODO: IMPLEMENT TYPE

        $type = VirusDataType::where('id', 'COVID-19')->first();

        $locations = State::all();

        return $locations->map(function ($location) {
            $totalConfirmed = VirusData::where('state', $location->id)->where('status', 'CONFIRMED')->orderBy('date','DESC')->first();
            $totalDeaths = VirusData::where('state', $location->id)->where('status', 'DEATHS')->orderBy('date','DESC')->first();
            $totalRecovered = VirusData::where('state', $location->id)->where('status', 'RECOVERED')->orderBy('date','DESC')->first();

            return [
                'id' => 'pin' . $location->id,
                'location' => [
                    'latitude' => $location->lat,
                    'longitude' => $location->lng
                ],
                'total_confirmed' => $totalConfirmed->count,
                'total_deaths' => $totalDeaths->count,
                'total_recovered' => $totalRecovered->count,
                'country' =>  ($location->name !== 'undefined' ? ltrim($location->name) . ', ' : '') . ltrim($location->country()->name)// TODO: ADD LTRIM TO IMPORT
            ];
        });
    }
}
