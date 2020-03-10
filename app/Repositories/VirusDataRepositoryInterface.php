<?php


namespace App\Repositories;


interface VirusDataRepositoryInterface
{
    public function store($data);

    public function getLatestCountryCount($country, $typeId, $statusId);

    public function getLatestStateCount($state, $typeId, $statusId);
}
