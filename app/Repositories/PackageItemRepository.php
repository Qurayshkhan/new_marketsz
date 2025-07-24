<?php

namespace App\Repositories;

use App\Interfaces\PackageInterface;
use App\Interfaces\PackageItemInterface;
use App\Models\Package;
use App\Models\PackageItem;
use App\Traits\CommonTrait;
use Carbon\Carbon;

class PackageItemRepository implements PackageItemInterface
{
    protected $packageItem;
    public function __construct(PackageItem $packageItem)
    {
        $this->packageItem = $packageItem;
    }

    public function store($data)
    {
        return $this->packageItem->create($data);
    }
    public function insert($items, $package)
    {
        foreach ($items as &$item) {
            $item['package_id'] = $package->id;
            $item['created_at'] = Carbon::now();
            $item['updated_at'] = Carbon::now();
        }
        return $this->packageItem->insert($items);
    }
}
