<?php


namespace App\Repositories;

use App\Models\State;
use App\Models\VirusData;
use Validator;

class VirusDataRepository implements VirusDataRepositoryInterface
{

    public function store($data)
    {
        $rules = [
            'state' => 'required',
            'date' => 'required',
            'type' => 'required',
            'status' => 'required',
            'count' => 'required'
        ];

        // Create a validator
        $validator = Validator::make($data, $rules);

        // Check if it fails, if it fail throw an exception with the errors
        if ($validator->fails()) {
            throw new Exception($validator->errors(), 401);
        }


        $existing = VirusData::where('date', $data['date'])->where('state', $data['state'])->where('type', $data['type'])->where('status', $data['status'])->first();
//        dd($existing);
        if ($existing) {
            $existing->update(['count' => $data['count']]);
            return $existing;
        } else {
            return VirusData::create($data);
        }
    }
}
