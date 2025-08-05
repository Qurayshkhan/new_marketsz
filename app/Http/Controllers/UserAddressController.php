<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\UserAddress;
use App\Repositories\UserAddressRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserAddressController extends Controller
{
    protected $userAddressRepository;

    public function __construct(UserAddressRepository $userAddressRepository)
    {
        $this->userAddressRepository = $userAddressRepository;
    }

    /**
     * Display a listing of the user's addresses.
     */
    public function index(): Response
    {
        $userAddresses = $this->userAddressRepository->userAddresses(Auth::id());

        return Inertia::render('Customers/Profile/EditTabs/AddressBook', [
            'userAddresses' => $userAddresses
        ]);
    }

    /**
     * Store a newly created address.
     */
    public function store(AddressRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Check if user has reached the limit of 12 addresses
        if ($user->addresses()->count() >= 12) {
            return redirect()->back()->withErrors(['message' => 'You can only save up to 12 addresses.']);
        }

        try {
            $data = $request->validated();
            $data['user_id'] = $user->id;

            $this->userAddressRepository->create($data);

            return redirect()->back()->with('alert', 'Address added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Failed to add address. Please try again.']);
        }
    }

    /**
     * Update the specified address.
     */
    public function update(AddressRequest $request, UserAddress $address): RedirectResponse
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $data = $request->validated();

            $this->userAddressRepository->update($address, $data);

            return redirect()->back()->with('success', 'Address updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Failed to update address. Please try again.']);
        }
    }

    /**
     * Remove the specified address.
     */
    public function destroy(UserAddress $address): RedirectResponse
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $this->userAddressRepository->delete($address);

            return redirect()->back()->with('success', 'Address deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Failed to delete address. Please try again.']);
        }
    }

    /**
     * Set an address as default for US or UK.
     */
    public function setDefault(Request $request, UserAddress $address): RedirectResponse
    {
        $request->validate([
            'type' => 'required|in:us,uk,both',
        ]);

        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $user = Auth::user();

            // Unset previous defaults based on type
            if ($request->type === 'us' || $request->type === 'both') {
                $this->userAddressRepository->unsetDefaultForType($user->id, 'us');
            }

            if ($request->type === 'uk' || $request->type === 'both') {
                $this->userAddressRepository->unsetDefaultForType($user->id, 'uk');
            }

            // Set new default
            $address->update([
                'is_default_us' => in_array($request->type, ['us', 'both']),
                'is_default_uk' => in_array($request->type, ['uk', 'both']),
            ]);

            $typeText = match ($request->type) {
                'us' => 'US',
                'uk' => 'UK',
                'both' => 'US & UK',
                default => 'Unknown'
            };

            return redirect()->back()->with('success', "Address set as default for {$typeText} successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Failed to set default address. Please try again.']);
        }
    }
}
