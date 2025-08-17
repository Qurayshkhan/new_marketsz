<?php


namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Request;

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

    public function update($userId, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        if ($data['date_of_birth']) {
            $data['date_of_birth'] = Carbon::parse($data['date_of_birth'])->format('Y-m-d');
        }
        return $this->user->where('id', $userId)->update($data);
    }

    public function users($request)
    {
        $query = $this->user->query();
        if ($request->search) {
            $query->whereLike('first_name', '%' . $request->search . '%')->orWhereLike('last_name', '%' . $request->search . '%')->orWhereLike('email', '%' . $request->search . '%')->orWhereLike('suite', '%' . $request->search . '%');
        }
        return $query->customer()->orderByDesc('id')->paginate(25);
    }

    public function store($data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        if ($data['date_of_birth']) {
            $data['date_of_birth'] = Carbon::parse($data['date_of_birth'])->format('Y-m-d');
        }
        return $this->user->create($data);

    }

    public function userCount()
    {
        return $this->user->customer()->active()->count();
    }

}
