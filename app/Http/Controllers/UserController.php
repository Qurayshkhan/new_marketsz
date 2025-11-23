<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Payments\Stripe;
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

    public function index(Request $request)
    {
        $users = $this->userRepository->users($request);
        return Inertia::render('Admin/Users/Report', ['users' => $users, 'filters' => ['search' => $request->input('search', '')]]);
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->userRepository->store($request->all());
            DB::commit();
            return Redirect::route('admin.users')->with('alert', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => $e->getMessage()]);
        }
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
