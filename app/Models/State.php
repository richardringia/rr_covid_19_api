<?php


namespace App\Models;


use App\Repositories\VirusDataRepository;
use App\Repositories\VirusDataRepositoryInterface;
use App\Repositories\VirusDataTypeRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;

    /**
     * The repository for managing the virus data.
     *
     * @var VirusDataRepositoryInterface
     */
    private $virusDataRepository;

    /**
     * Country constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->virusDataRepository = new VirusDataRepository(new VirusDataTypeRepository());
    }

    protected $fillable = [
        'id', 'name', 'lat', 'lng', 'country'
    ];

    public function country() {
        return $this->hasOne(Country::class, 'id', 'country')->first();
    }

    public function totalConfirmed($typeId) {
        return $this->total($typeId, 'CONFIRMED');
    }

    public function totalDeaths($typeId) {
        return $this->total($typeId, 'DEATHS');
    }

    public function totalRecovered($typeId) {
        return $this->total($typeId, 'RECOVERED');
    }

    private function total($typeId, $statusId) {
        return $this->virusDataRepository->getLatestStateCount($this, $typeId, $statusId);
    }
}
