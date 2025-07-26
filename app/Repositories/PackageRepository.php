<?php

namespace App\Repositories;

use App\Interfaces\PackageInterface;
use App\Models\Package;

class PackageRepository implements PackageInterface
{

    protected $package;
    public function __construct(Package $package)
    {
        $this->package = $package;
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

}
