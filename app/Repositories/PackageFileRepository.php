<?php

namespace App\Repositories;

use App\Interfaces\PackageFileInterface;
use App\Models\PackageFile;
use App\Traits\CommonTrait;
use Carbon\Carbon;

class PackageFileRepository implements PackageFileInterface
{
    use CommonTrait;
    protected $packageFile;
    public function __construct(PackageFile $packageFile)
    {
        $this->packageFile = $packageFile;
    }
    public function store($data)
    {
        return $this->packageFile->create($data);
    }

    public function insert($files, $package)
    {
        $this->packageFile->where('package_id', $package->id)->delete();
        $data = [];
        foreach ($files as $file) {
            $path = $this->addFile($file, 'uploads/package_files/');
            $data[] = [
                'file' => $path,
                'package_id' => $package->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        return $this->packageFile->insert($data);
    }

    public function deletePackageFilesByPackageId($packageId)
    {
        return $this->packageFile->where('package_id', $packageId)->delete();
    }
}
