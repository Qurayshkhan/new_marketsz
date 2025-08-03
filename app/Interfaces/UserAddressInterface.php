<?php

namespace App\Interfaces;

use App\Models\UserAddress;

interface UserAddressInterface
{
    public function userAddresses($userId);

    public function create(array $data);

    public function update(UserAddress $address, array $data);

    public function delete(UserAddress $address);

    public function findById($id, $userId);

    public function getDefaultAddresses($userId);

    public function unsetDefaultForType($userId, $type);
}
