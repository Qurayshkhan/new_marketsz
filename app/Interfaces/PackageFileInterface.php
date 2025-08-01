<?php


namespace App\Interfaces;

interface PackageFileInterface
{
    public function insert($files, $package);

    public function deletePackageFilesByPackageId($packageId);

    public function getPackageFiles($packageId);
}
