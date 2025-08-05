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

    public function shipmentPackages($userId, $status = null)
    {
        $query = $this->package->query();
        if ($userId) {
            $query->where('sender_id', $userId);
        }
        if ($status == null) {
            $query->whereIn('status', [PackageStatus::ACTION_REQUIRED, PackageStatus::IN_REVIEW, PackageStatus::READY_TO_SEND]);
        } else {
            $query->where('status', $status);
        }
        return $query->with('files', 'items', 'customer', 'specialRequest')->get();
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

    public function packageCounts($userId)
    {
        return [
            'action_required' => $this->shipmentPackages($userId, PackageStatus::ACTION_REQUIRED)->count(),
            'in_review' => $this->shipmentPackages($userId, PackageStatus::IN_REVIEW)->count(),
            'ready_to_send' => $this->shipmentPackages($userId, PackageStatus::READY_TO_SEND)->count(),
            'all' => $this->shipmentPackages($userId)->count(),
        ];
    }

    public function getPackageByIds($ids)
    {
        return $this->package->whereIn('id', $ids)->get();
    }

}
