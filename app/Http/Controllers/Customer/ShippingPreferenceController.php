<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Repositories\ShippingPreferencesRepository;
use App\Repositories\UserAddressRepository;
use Auth;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class ShippingPreferenceController extends Controller
{
    protected $shippingPreferenceRepository, $userAddressRepository;
    public function __construct(ShippingPreferencesRepository $shippingPreferenceRepository, UserAddressRepository $userAddressRepository)
    {
        $this->shippingPreferenceRepository = $shippingPreferenceRepository;
        $this->userAddressRepository = $userAddressRepository;
    }

    public function index()
    {
        return Inertia::render('Customers/Profile/EditTabs/ShippingPreferences', [
            'shippingPreference' => $this->shippingPreferenceRepository->getShippingPreference(Auth::id()),
            'preferredShipMethods' => $this->shippingPreferenceRepository->getPreferredShipMethod(),
            'internationalShippingOptions' => $this->shippingPreferenceRepository->getInternationalShippingOptions(),
            'shippingPreferenceOptions' => $this->shippingPreferenceRepository->shippingPreferenceOptions(),
            'packingOptions' => $this->shippingPreferenceRepository->getPackingOption(),
            'proformaInvoiceOptions' => $this->shippingPreferenceRepository->getProformaInvoiceOptions(),
            'loginOptions' => $this->shippingPreferenceRepository->getLoginOptions(),
            'addresses' => $this->userAddressRepository->getDefaultAddresses(Auth::id()),
        ]);
    }

    public function setPreferAddress(Request $request)
    {
        try {
            DB::beginTransaction();
            $address = $this->userAddressRepository->getDefaultAddressByType($request->address_type);
            if ($address) {
                $this->shippingPreferenceRepository->updateOrCreateShippingPreference(['user_address_id' => $address->id]);
            }
            DB::commit();
            return Redirect::route('customer.shippingPreferences.preference')->with('alert', 'Set address preference successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('customer.shippingPreferences.preference')->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function saveChangePreferences(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = [
                'preferred_ship_method' => $request->preferred_ship_method,
                'international_shipping_option' => $request->international_shipping_option,
                'shipping_preference_option' => json_encode($request->shipping_preference_option),
                'packing_option' => json_encode($request->packing_option),
                'proforma_invoice_options' => json_encode($request->proforma_invoice_options),
                'login_option' => json_encode($request->login_option),
                'tax_id' => $request->tax_id,
                'additional_email' => $request->additional_email,
                'maximum_weight_per_box' => $request->maximum_weight_per_box,
            ];
            $this->shippingPreferenceRepository->updateOrCreateShippingPreference($data);
            DB::commit();
            return Redirect::route('customer.shippingPreferences.preference')->with('alert', 'Preferences updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('customer.shippingPreferences.preference')->withErrors(['message' => $e->getMessage()]);
        }
    }
}
