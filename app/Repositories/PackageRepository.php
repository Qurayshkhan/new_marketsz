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

    public function packages($filters = [])
    {
        $query = $this->package->query();

        // Apply status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply date range filter
        if (!empty($filters['date_from'])) {
            $query->where('date_received', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('date_received', '<=', $filters['date_to']);
        }

        // Apply sender filter
        if (!empty($filters['sender_id'])) {
            $query->where('sender_id', $filters['sender_id']);
        }

        // Apply suite filter
        if (!empty($filters['suite'])) {
            $query->whereHas('customer', function ($q) use ($filters) {
                $q->where('suite', $filters['suite']);
            });
        }

        // Apply tracking ID filter
        if (!empty($filters['tracking_id'])) {
            $query->where('tracking_id', 'LIKE', '%' . $filters['tracking_id'] . '%');
        }

        // Apply total value range filter
        if (!empty($filters['total_value_min'])) {
            $query->where('total_value', '>=', $filters['total_value_min']);
        }
        if (!empty($filters['total_value_max'])) {
            $query->where('total_value', '<=', $filters['total_value_max']);
        }

        return $query->with('customer')->orderBy('created_at', 'desc')->paginate(25);
    }
    public function allPackages()
    {
        return $this->package->with('customer')->get();
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
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', $status);
            }
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
    public function sumWeightPackageByIds($ids)
    {
        return $this->package->whereIn('id', $ids)->sum('weight');
    }

    public function findById($id)
    {
        return $this->package->findOrFail($id);
    }
}
