<?php


namespace App\Repositories;


interface VirusDataTypeRepositoryInterface
{
    public function store($name);

    public function getById($id);
}
