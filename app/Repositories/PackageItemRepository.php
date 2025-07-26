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
        $exitItems = $this->getPackageById($package->id);
        if (count($exitItems) > 0) {
            $this->itemsDeleteByPackageId($package->id);
        }
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'package_id' => $package->id,
                'title' => $item['title'],
                'description' => $item['description'],
                'item_note' => $item['item_note'],
                'quantity' => $item['quantity'],
                'value_per_unit' => $item['value_per_unit'],
                'total_line_value' => $item['total_line_value'],
                'total_line_weight' => $item['total_line_weight'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        return $this->packageItem->insert($data);
    }

    public function getPackageById($packageId)
    {
        return $this->packageItem->where('package_id', $packageId)->get();
    }

    public function itemsDeleteByPackageId($packageId)
    {
        return $this->packageItem->where('package_id', $packageId)->delete();
    }
}
