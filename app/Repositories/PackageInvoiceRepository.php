<?php

namespace App\Repositories;

use App\Helpers\PackageStatus;
use App\Interfaces\PackageInterface;
use App\Interfaces\PackageInvoiceInterface;
use App\Models\Package;
use App\Models\PackageInvoice;
use App\Models\SpecialRequest;
use Auth;

class PackageInvoiceRepository implements PackageInvoiceInterface
{

    protected $packageInvoice;
    public function __construct(PackageInvoice $packageInvoice)
    {
        $this->packageInvoice = $packageInvoice;
    }


    public function uploadInvoices($path, $package_id)
    {
        return $this->packageInvoice->create(['package_id' => $package_id, 'image' => $path]);
    }
}
