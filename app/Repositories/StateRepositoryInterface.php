<?php


namespace App\Repositories;


interface StateRepositoryInterface
{
    public function store($name, $country, $lat, $lng);
}
