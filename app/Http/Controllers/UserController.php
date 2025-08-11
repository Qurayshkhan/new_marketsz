<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class UserController extends Controller
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->users();
        return Inertia::render('Admin/Users/Report', ['users' => $users]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/EditTabs/Basic', ['user' => $user]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            DB::beginTransaction();
            $this->userRepository->update($user->id, $request->all());
            DB::commit();
            return Redirect::back()->with('alert', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
