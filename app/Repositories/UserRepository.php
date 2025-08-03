<?php


namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;

class UserRepository implements UserInterface
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function customers()
    {
        return $this->user->active()->customer()->get();
    }

    public function findById($userId)
    {
        return $this->user->find($userId);
    }

    public function updateUser($userId, $data)
    {
        return $this->user->where('id', $userId)->update($data);
    }

}
