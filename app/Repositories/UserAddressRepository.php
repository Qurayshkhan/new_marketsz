<?php


namespace App\Repositories;

use App\Interfaces\UserAddressInterface;
use App\Models\User;
use App\Models\UserAddress;
use Auth;

class UserAddressRepository implements UserAddressInterface
{
    protected $userAddress;

    public function __construct(UserAddress $userAddress)
    {
        $this->userAddress = $userAddress;
    }

    public function userAddresses($userId)
    {
        return $this->userAddress->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create(array $data)
    {
        return $this->userAddress->create($data);
    }

    public function update(UserAddress $address, array $data)
    {
        return $address->update($data);
    }

    public function delete(UserAddress $address)
    {
        return $address->delete();
    }

    public function findById($id, $userId)
    {
        return $this->userAddress->where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function getDefaultAddresses($userId)
    {
        return $this->userAddress->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('is_default_us', true)
                    ->orWhere('is_default_uk', true);
            })
            ->get();
    }

    public function unsetDefaultForType($userId, $type)
    {
        $field = match ($type) {
            'us' => 'is_default_us',
            'uk' => 'is_default_uk',
            default => null
        };

        if (!$field) {
            return false;
        }

        return $this->userAddress->where('user_id', $userId)
            ->where($field, true)
            ->update([$field => false]);
    }

    public function getDefaultAddressByType($type)
    {
        $query = $this->userAddress->query()->where('user_id', Auth::id());
        if ($type == "is_uk") {
            $query->where('is_default_uk', true);
        } else if ($type == "is_us") {
            $query->where('is_default_us', true);
        }
        return $query->first();
    }
}
