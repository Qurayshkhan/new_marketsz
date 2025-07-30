<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Repositories\PackageRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuiteController extends Controller
{
    protected $packageRepository;
    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }
    public function index()
    {
        // return Inertia::render('Customers/Suite/Report');

        return redirect()->route('customer.suiteActionRequired');
    }

    public function actionRequired()
    {
        return Inertia::render('Customers/Suite/SuitTabs/ActionRequired', [
            'actions' => $this->packageRepository->userActionRequiredPackage(),
            'specialRequests' => $this->packageRepository->packageSpecialRequests(),
        ]);
    }
}
