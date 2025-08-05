<?php

namespace App\Repositories;

use App\Interfaces\ShipInterface;
use App\Models\Ship;

class ShipRepository implements ShipInterface
{
    protected $ship;

    public function __construct(Ship $ship)
    {
        $this->ship = $ship;
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
}
