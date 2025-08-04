<?php

namespace App\Interfaces;

interface ShippingPreferencesInterface
{
    public function getShippingPreference($userId);
    public function getPackingOption();
    public function getInternationalShippingOptions();
    public function getPreferredShipMethod();

    public function getLoginOptions();

    public function getProformaInvoiceOptions();

    public function shippingPreferenceOptions();
}
