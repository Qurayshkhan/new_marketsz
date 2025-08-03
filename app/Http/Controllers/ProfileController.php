<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\UserAddress;
use App\Repositories\UserAddressRepository;
use App\Traits\CommonTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    use CommonTrait;
    protected $userAddressRepository;
    public function __construct(UserAddressRepository $userAddressRepository)
    {
        $this->userAddressRepository = $userAddressRepository;
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::back();
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/login');
    }

    public function customerProfile(Request $request)
    {
        return Inertia::render('Customers/Profile/EditTabs/AccountSetting', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }
    public function addressBook()
    {
        $userAddresses = $this->userAddressRepository->userAddresses(Auth::id());
        return Inertia::render('Customers/Profile/EditTabs/AddressBook', ['userAddresses' => $userAddresses]);
    }
    public function addressStore(Request $request)
    {
        $user = $request->user();
        if ($user->addresses()->count() >= 12) {
            return Redirect::back()->withErrors(['message' => 'You can only save up to 12 addresses.']);
        }

        $validated = $request->validate([
            'address_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country_code' => 'required|string|max:10',
            'phone_number' => 'required|string|max:20',
        ]);
        try {
            $address = $user->addresses()->create($validated);

            return Redirect::back()->with('alert', 'Address added successfully.');
        } catch (\Exception $e) {
            return Redirect::back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function setDefault(Request $request, $id)
    {

        $request->validate([
            'type' => 'required|in:us,uk,both',
        ]);

        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        if ($request->type === 'us' || $request->type === 'both') {

            $user->addresses()->where('is_default_us', true)->update(['is_default_us' => false]);
        }

        if ($request->type === 'uk' || $request->type === 'both') {

            $user->addresses()->where('is_default_uk', true)->update(['is_default_uk' => false]);
        }


        $address->update([
            'is_default_us' => in_array($request->type, ['us', 'both']),
            'is_default_uk' => in_array($request->type, ['uk', 'both']),
        ]);

        return Redirect::route('customer.account.addressBook')->with('alert', "Default address updated.");
    }

    public function updateUserAddress(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'address_name' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);
        try {
            $address = UserAddress::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

            $address->update($request->only([
                'full_name',
                'address_name',
                'address_line_1',
                'city',
                'state',
                'postal_code',
                'country',
                'phone_number',
            ]));
            return Redirect::route('customer.account.addressBook')->with('alert', "Address updated successfully.");
        } catch (\Exception $e) {
            return Redirect::back()->withErrors(['message' => $e->getMessage()]);
        }

    }



}
