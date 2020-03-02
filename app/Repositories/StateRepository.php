<?php


namespace App\Repositories;


use App\Models\State;

class StateRepository implements StateRepositoryInterface
{

    public function store($name, $country, $lat, $lng)
    {
        $existing = State::where('name', $name)->where('country', $country)->first();
        if (!$existing) {
            return State::create([
                'name' => $name,
                'country' => $country,
                'lat' => $lat,
                'lng' => $lng
            ]);
        }
        return $existing;
    }
}
