<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\VirusData;
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

        $data = $locations->map(function ($location) use ($type) {
            $totalConfirmed = VirusData::where('state', $location->id)->where('status', 'CONFIRMED')->where('type', $type->id)->orderBy('date', 'DESC')->first();
            $totalDeaths = VirusData::where('state', $location->id)->where('status', 'DEATHS')->where('type', $type->id)->orderBy('date', 'DESC')->first();
            $totalRecovered = VirusData::where('state', $location->id)->where('status', 'RECOVERED')->where('type', $type->id)->orderBy('date', 'DESC')->first();

            return [
                'id' => 'pin' . $location->id,
                'location' => [
                    'latitude' => $location->lat,
                    'longitude' => $location->lng
                ],
                'total_confirmed' => $totalConfirmed->count,
                'total_deaths' => $totalDeaths->count,
                'total_recovered' => $totalRecovered->count,
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

        $data = $countries->map(function ($country) use ($type) {
            $country->name = $country->name();
            $totalConfirmedCount = 0;
            $totalDeathsCount = 0;
            $totalRecoveredCount = 0;
            foreach ($country->states as $state) {
                $totalConfirmed = VirusData::where('state', $state->id)->where('status', 'CONFIRMED')->where('type', $type->id)->orderBy('date', 'DESC')->first();
                $totalDeaths = VirusData::where('state', $state->id)->where('status', 'DEATHS')->where('type', $type->id)->orderBy('date', 'DESC')->first();
                $totalRecovered = VirusData::where('state', $state->id)->where('status', 'RECOVERED')->where('type', $type->id)->orderBy('date', 'DESC')->first();
                $totalConfirmedCount += $totalConfirmed->count;
                $totalDeathsCount += $totalDeaths->count;
                $totalRecoveredCount += $totalRecovered->count;
            }
            return [
                'country' => $country,
                'total_confirmed' => $totalConfirmedCount,
                'total_deaths' => $totalDeathsCount,
                'total_recovered' => $totalRecoveredCount,
            ];
        });

        return response()->json(['updated' => true, 'countries' => $data], 200);
    }
}
