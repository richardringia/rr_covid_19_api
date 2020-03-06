<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\VirusDataType;
use App\Models\VirusDataUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VirusDataController extends Controller
{
    public function checkForUpdate(Request $request)
    {
        $deviceUpdatedDate = $request->get('updated-date');
        if ($deviceUpdatedDate != "") {
            $latestUpdate = VirusDataUpdate::latest('date')->first();
            if ($latestUpdate != null) {
                $latestUpdateDate = Carbon::parse($latestUpdate->date);
                $deviceUpdatedDate = Carbon::parse(\DateTime::createFromFormat('D M d Y H:i:s e+', $deviceUpdatedDate));

                if (!$latestUpdateDate->gt($deviceUpdatedDate)) {
                    return response()->json(['updated' => false], 200);
                }

            }
        }

        return false;
    }


    public function all(Request $request)
    {
        try {
            $update = $this->checkForUpdate($request);
            if ($update)
                return $update;
        } catch (\Exception $e) {
            throw new \Exception("Date error", 401);
        }

        $type = VirusDataType::where('id', 'COVID-19')->first();

        $locations = State::all();

        $data = $locations->map(function (State $location) use ($type) {
            return [
                'id' => 'pin' . $location->id,
                'location' => [
                    'latitude' => $location->lat,
                    'longitude' => $location->lng
                ],
                'total_confirmed' =>  $location->totalConfirmed($type->id),
                'total_deaths' => $location->totalDeaths($type->id),
                'total_recovered' => $location->totalRecovered($type->id),
                'country' => ($location->name !== 'undefined' ? ltrim($location->name) . ', ' : '') . ltrim($location->country()->name())// TODO: ADD LTRIM TO IMPORT
            ];
        });

        return response()->json(['updated' => true, 'covid' => $data], 200);
    }

    public function allByCountry(Request $request)
    {
        try {
            $update = $this->checkForUpdate($request);
            if ($update)
                return $update;
        } catch (\Exception $e) {
            throw new \Exception("Date error", 401);
        }

        $type = VirusDataType::where('id', 'COVID-19')->first();


        $countries = Country::all();

        $data = $countries->map(function (Country $country) use ($type) {
            $country->name = $country->name();
            $states = $country->states->map(function (State $state) use ($type) {
                return [
                    'id' => $state->name,
                    'state' => $state,
                    'location' => [
                        'latitude' => $state->lat,
                        'longitude' => $state->lng
                    ],
                    'total_confirmed' => $state->totalConfirmed($type->id),
                    'total_deaths' => $state->totalDeaths($type->id),
                    'total_recovered' => $state->totalRecovered($type->id),
                ];
            });
            unset($country->states);
            return [
                'country' => $country,
                'states' => $states,
                'total_confirmed' => $country->totalConfirmed($type->id),
                'total_deaths' => $country->totalDeaths($type->id),
                'total_recovered' => $country->totalRecovered($type->id),
            ];
        });

        return response()->json(['updated' => true, 'countries' => $data], 200);
    }
}
