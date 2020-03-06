<?php


namespace App\Repositories;

use App\Models\Country;
use App\Models\State;
use App\Models\VirusData;
use Validator;

class VirusDataRepository implements VirusDataRepositoryInterface
{
    /**
     * The repository of managing types of virus data
     *
     * @var VirusDataTypeRepositoryInterface
     */
    private $virusDataTypeRepository;

    /**
     * VirusDataRepository constructor.
     * @param VirusDataTypeRepositoryInterface $virusDataTypeRepository
     */
    public function __construct(VirusDataTypeRepositoryInterface $virusDataTypeRepository)
    {
        $this->virusDataTypeRepository = $virusDataTypeRepository;
    }

    public function store($data)
    {
        $rules = [
            'state' => 'required',
            'date' => 'required',
            'type' => 'required',
            'status' => 'required',
            'count' => 'required'
        ];

        if ($data['count'] === "") $data['count'] = 0;

        // Create a validator
        $validator = Validator::make($data, $rules);

        // Check if it fails, if it fail throw an exception with the errors
        if ($validator->fails()) {
            throw new \Exception($validator->errors(), 401);
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

    public function getLatestCountryCount($country, $typeId, $statusId)
    {
        $counter = 0;
        foreach ($country->states as $state) {
            $counter += $this->getLatestStateCount($state, $typeId, $statusId);
        }
        return $counter;
    }

    public function getLatestStateCount($state, $typeId, $statusId)
    {
        $virusData = VirusData::where('state', $state->id)->where('status', $statusId)->where('type', $typeId)->orderBy('date', 'DESC')->firstOrFail();
        return $virusData->count;
    }
}
