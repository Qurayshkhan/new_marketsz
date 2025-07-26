<?php


namespace App\Interfaces;

interface PackageInterface
{

    public function packages();
    public function store($data);

    public function deletePackage($packageId);
}
