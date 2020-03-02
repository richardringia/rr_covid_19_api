<?php

namespace App\Console\Commands;

use App\Models\VirusDataStatus;
use App\Repositories\CountryRepositoryInterface;
use App\Repositories\StateRepositoryInterface;
use App\Repositories\VirusDataRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportCSSEGISandData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'virus_data:import_cssegi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The repository for managing the virus data.
     *
     * @var VirusDataRepositoryInterface
     */
    private $virusDataRepository;

    /**
     * The repository for managing the states.
     *
     * @var StateRepositoryInterface
     */
    private $stateRepository;

    /**
     * The repository for managing the states.
     *
     * @var CountryRepositoryInterface
     */
    private $countryRepository;

    /**
     * Virus data type
     *
     * @var string;
     */
    private $virusDataType = "COVID-19";

    /**
     * Create a new command instance.
     *
     * @param VirusDataRepositoryInterface $virusDataRepository
     * @param StateRepositoryInterface $stateRepository
     * @param CountryRepositoryInterface $countryRepository
     */
    public function __construct(VirusDataRepositoryInterface $virusDataRepository, StateRepositoryInterface $stateRepository, CountryRepositoryInterface $countryRepository)
    {
        parent::__construct();
        $this->virusDataRepository = $virusDataRepository;
        $this->stateRepository = $stateRepository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $virusDataStatuses = VirusDataStatus::all();

        foreach ($virusDataStatuses as $virusDataStatus) {
            if ($virusDataStatus->url != null) {
                $csvData = file_get_contents($virusDataStatus->url);
                $csvDelimiter = ',';
                $csvLines = str_getcsv($csvData, "\n");
                $counter = 0;
                $dates = [];
                foreach ($csvLines as $row) {
                    $row = str_getcsv($row, $csvDelimiter);
                    if ($counter == 0) {
                        $rowCounter = 0;
                        foreach ($row as $rowItem) {
                            if ($rowCounter > 3) {
                                array_push($dates, Carbon::parse($rowItem));
                            }
                            $rowCounter++;
                        }
                    } else {
                        if (count($dates) > 0) {
                            $skipRows = 0;
                            if (!is_numeric($row[2])) {
                                $skipRows = 1;
                            }

                            $country = $this->countryRepository->store($row[1]);
                            $state = $this->stateRepository->store($row[0] == "" ? "undefined" : $row[0], $country->id, $row[2 + $skipRows], $row[3  + $skipRows]);

                            foreach ($dates as $key => $date) {
                                $count = $row[$key + 4 + $skipRows];
                                $this->virusDataRepository->store([
                                    'state' => $state->id,
                                    'date' => $date,
                                    'type' => $this->virusDataType,
                                    'status' => $virusDataStatus->id,
                                    'count' => $count
                                ]);
                            }

                        }
                    }

                    $counter++;
                }
            }
        }

        $this->line('Corona imported!');
    }
}
