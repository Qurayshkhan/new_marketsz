<?php

namespace App\Interfaces;

use App\Models\Ship;

interface ShipInterface
{
    public function create(array $data);

    public function update(Ship $ship, array $data);

    public function findById($id);

    public function delete(Ship $ship);

    public function getAllShips();

    public function getShipsByUserId($userId);

    public function getShipsWithPackages();

    public function getShipByTrackingNumber($trackingNumber);

    public function getShipsByStatus($status);

    public function getShipDetails($ship);
}
