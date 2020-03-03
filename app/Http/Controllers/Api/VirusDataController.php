<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\VirusData;
use App\Models\VirusDataType;
use App\Models\VirusDataUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VirusDataController extends Controller
{
    public function all(Request $request) {

        $deviceUpdatedDate = $request->get('updated-date');
        if ($deviceUpdatedDate != "") {
            try {
                $latestUpdate = VirusDataUpdate::latest('date')->first();
                if ($latestUpdate != null) {
                    $latestUpdateDate = Carbon::parse($latestUpdate->date);
                    $deviceUpdatedDate = Carbon::parse(\DateTime::createFromFormat('D M d Y H:i:s e+', $deviceUpdatedDate));


                    if (!$latestUpdateDate->gt($deviceUpdatedDate)) {
                        return response()->json(['updated' => false], 200);
                    }
                }

            } catch (\Exception $e) {
                throw new \Exception("Date error", 401);
            }
        }
        //TODO: IMPLEMENT TYPE

        $type = VirusDataType::where('id', 'COVID-19')->first();

        $locations = State::all();

         $data = $locations->map(function ($location) {
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

        return response()->json(['updated' => true, 'covid' => $data], 200);
    }
}
