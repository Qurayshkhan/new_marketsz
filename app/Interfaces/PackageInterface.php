<?php


namespace App\Interfaces;

use App\Helpers\PackageStatus;

interface PackageInterface
{

    public function packages();
    public function store($data);

    public function deletePackage($packageId);

    public function shipmentPackages($userId, $status = PackageStatus::ACTION_REQUIRED);

    public function packageSpecialRequests();

    public function addPackageNote($data);

    public function changeStatus($data);

    public function packageCounts($userId);
}
