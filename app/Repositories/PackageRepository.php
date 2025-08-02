<?php

namespace App\Repositories;

use App\Helpers\PackageStatus;
use App\Interfaces\PackageInterface;
use App\Models\Package;
use App\Models\SpecialRequest;
use Auth;

class PackageRepository implements PackageInterface
{

    protected $package, $specialRequest;
    public function __construct(Package $package, SpecialRequest $specialRequest)
    {
        $this->package = $package;
        $this->specialRequest = $specialRequest;
    }

    public function packages()
    {
        return $this->package->paginate(25);
    }
    public function store($data)
    {
        $packageId = isset($data['id']) ? $data['id'] : null;
        return $this->package->updateOrCreate(['id' => $packageId], $data);
    }

    public function deletePackage($packageId)
    {
        return $this->package->where('id', $packageId)->delete();
    }

    public function userActionRequiredPackage()
    {
        return $this->package->where('sender_id', Auth::id())->where('status', PackageStatus::ACTION_REQUIRED)->with('files', 'items', 'customer', 'specialRequest')->get();
    }

    public function packageSpecialRequests()
    {
        return $this->specialRequest->get();
    }

    public function addPackageNote($data)
    {
        $this->package->updateOrCreate(['id' => $data['id']], [
            'note' => $data['note'],
        ]);
    }

    public function changeStatus($data)
    {
        return $this->package->where('id', $data['package_id'])->update(['status' => $data['status']]);
    }



}
