<?php

namespace App\Repositories;

use App\Interfaces\ShipInterface;
use App\Models\Ship;
use App\Models\ShippingPricing;
use Inertia\Inertia;

class ShipRepository implements ShipInterface
{
    protected $ship, $shipPricing;

    public function __construct(Ship $ship, ShippingPricing $shipPricing)
    {
        $this->ship = $ship;
        $this->shipPricing = $shipPricing;
    }

    public function create(array $data)
    {
        return $this->ship->create($data);
    }

    public function update(Ship $ship, array $data)
    {
        return $ship->update($data);
    }

    public function findById($id)
    {
        return $this->ship->findOrFail($id);
    }

    public function delete(Ship $ship)
    {
        return $ship->delete();
    }

    public function getAllShips()
    {
        return $this->ship->all();
    }
    public function getShipsByUserId($userId)
    {
        return $this->ship->where('user_id', $userId)->get();
    }

    public function getShipsWithPackages()
    {
        return $this->ship->with('packages')->get();
    }

    public function getShipByTrackingNumber($trackingNumber)
    {
        return $this->ship->where('tracking_number', $trackingNumber)->first();
    }

    public function getShipsByStatus($status)
    {
        return $this->ship->where('status', $status)->get();
    }

    public function getShipsByUserIdWithPackages($userId)
    {
        return $this->ship->where('user_id', $userId)->with('packages')->get();
    }

    public function attachPackageToShip($shipId, $packageId)
    {
        $ship = $this->findById($shipId);
        $ship->packages()->attach($packageId);
        return $ship;
    }

    public function getShipPriceByWightAndService($weight, $shippingMethodName)
    {
        return $this->shipPricing->where('type', 'Weight')->where('range_value', $weight)
            ->where('service', $shippingMethodName)
            ->first();
    }
    public function getShipPriceByVolumeAndService($volume, $shippingMethodName)
    {
        return $this->shipPricing
            ->where('type', 'Volume')
            ->where('service', $shippingMethodName)
            ->where('range_value', '<=', $volume)
            ->where(function ($query) use ($volume) {
                $query->where('range_to', '>=', $volume)
                    ->orWhereNull('range_to');
            })
            ->orderBy('range_value', 'desc')
            ->first();
    }

    public function getShipDetails($shipId)
    {
        return $this->ship->where('id', $shipId)->with('user', 'userAddress', 'packages', 'internationalShipping')->first();
    }
}
