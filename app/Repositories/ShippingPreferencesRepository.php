<?php

namespace App\Repositories;

use App\Interfaces\ShippingPreferencesInterface;
use App\Models\InternationalShippingOptions;
use App\Models\LoginOption;
use App\Models\PackingOptions;
use App\Models\PreferredShipMethod;
use App\Models\ProformaInvoiceOptions;
use App\Models\ShippingPreferenceOption;
use App\Models\ShippingPreferences;
use Auth;

class ShippingPreferencesRepository implements ShippingPreferencesInterface
{
    protected $shippingPreferences, $packingOption, $internationalShippingOptions, $preferredShipMethod, $proformaInvoiceOptions, $loginOption, $shippingPreferenceOption;

    public function __construct(ShippingPreferences $shippingPreferences, PackingOptions $packingOption, InternationalShippingOptions $internationalShippingOptions, PreferredShipMethod $preferredShipMethod, ProformaInvoiceOptions $proformaInvoiceOptions, LoginOption $loginOption, ShippingPreferenceOption $shippingPreferenceOption)
    {
        $this->shippingPreferences = $shippingPreferences;
        $this->packingOption = $packingOption;
        $this->internationalShippingOptions = $internationalShippingOptions;
        $this->preferredShipMethod = $preferredShipMethod;
        $this->proformaInvoiceOptions = $proformaInvoiceOptions;
        $this->loginOption = $loginOption;
        $this->shippingPreferenceOption = $shippingPreferenceOption;
    }

    public function getShippingPreference($userId)
    {
        return $this->shippingPreferences->where('user_id', $userId)->with('address')->first();
    }
    public function getPackingOption()
    {
        return $this->packingOption->get();
    }

    public function getInternationalShippingOptions()
    {
        return $this->internationalShippingOptions->get();
    }

    public function getPreferredShipMethod()
    {
        return $this->preferredShipMethod->get();
    }

    public function getLoginOptions()
    {
        return $this->loginOption->get();
    }

    public function getProformaInvoiceOptions()
    {
        return $this->proformaInvoiceOptions->get();
    }

    public function shippingPreferenceOptions()
    {
        return $this->shippingPreferenceOption->get();
    }

    public function updateOrCreateShippingPreference($data)
    {
        return $this->shippingPreferences->updateOrCreate(['user_id' => Auth::id()], $data);
    }

    public function sumPackingOption($packingOptionIds)
    {
        return $this->packingOption->whereIn('id', $packingOptionIds)->sum('price');
    }

    public function sumShippingPreferenceOption($shippingPreferenceOptionIds)
    {
        return $this->shippingPreferenceOption->whereIn('id', $shippingPreferenceOptionIds)->sum('price');
    }


}
