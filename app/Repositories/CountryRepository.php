<?php


namespace App\Repositories;


use App\Models\Country;

class CountryRepository implements CountryRepositoryInterface
{

    public function store($name)
    {
        $existing = Country::where('name', $name)->first();
        if (!$existing) {
            return Country::create([
                'name' => $name
            ]);
        }
        return $existing;
    }
}
