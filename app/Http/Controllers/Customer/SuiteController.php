<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\PackageStatus;
use App\Http\Controllers\Controller;
use App\Models\InternationalShippingOptions;
use App\Repositories\PackageFileRepository;
use App\Repositories\PackageInvoiceRepository;
use App\Repositories\PackageRepository;
use App\Repositories\ShippingPreferencesRepository;
use App\Repositories\ShipRepository;
use App\Traits\CommonTrait;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class SuiteController extends Controller
{
    use CommonTrait;
    protected $packageRepository, $packageInvoiceRepository, $packageFileRepository, $shipPreferencesRepository, $shipRepository;
    public function __construct(PackageRepository $packageRepository, PackageInvoiceRepository $packageInvoiceRepository, PackageFileRepository $packageFileRepository, ShippingPreferencesRepository $shippingPreferencesRepository, ShipRepository $shipRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->packageInvoiceRepository = $packageInvoiceRepository;
        $this->packageFileRepository = $packageFileRepository;
        $this->shipPreferencesRepository = $shippingPreferencesRepository;
        $this->shipRepository = $shipRepository;
    }
    public function index()
    {
        return redirect()->route('customer.suiteActionRequired');
    }

    public function actionRequired()
    {
        return Inertia::render('Customers/Suite/SuitTabs/ActionRequired', [
            'actions' => $this->packageRepository->shipmentPackages(Auth::id(), PackageStatus::ACTION_REQUIRED),
            'specialRequests' => $this->packageRepository->packageSpecialRequests(),
            'packageCounts' => $this->packageRepository->packageCounts(Auth::id()),
        ]);
    }

    public function inReview()
    {
        return Inertia::render('Customers/Suite/SuitTabs/InReview', [
            'inReviews' => $this->packageRepository->shipmentPackages(Auth::id(), PackageStatus::IN_REVIEW),
            'specialRequests' => $this->packageRepository->packageSpecialRequests(),
            'packageCounts' => $this->packageRepository->packageCounts(Auth::id()),
        ]);
    }
    public function readyToSend()
    {
        return Inertia::render('Customers/Suite/SuitTabs/ReadyToSend', [
            'readyToSends' => $this->packageRepository->shipmentPackages(Auth::id(), PackageStatus::READY_TO_SEND),
            'specialRequests' => $this->packageRepository->packageSpecialRequests(),
            'packageCounts' => $this->packageRepository->packageCounts(Auth::id()),
        ]);
    }

    public function viewAll()
    {
        return Inertia::render('Customers/Suite/SuitTabs/ViewAll', [
            'viewAllPackages' => $this->packageRepository->shipmentPackages(Auth::id()),
            'specialRequests' => $this->packageRepository->packageSpecialRequests(),
            'packageCounts' => $this->packageRepository->packageCounts(Auth::id()),
        ]);
    }

    public function addNote(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->packageRepository->addPackageNote($request->all());
            DB::commit();

            return response()->json(['message' => 'Note added successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function uploadInvoices(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->packageRepository->changeStatus($request->all());
            if (count($request->invoices) > 0) {
                foreach ($request->invoices as $invoice) {
                    $path = $this->addFile($invoice, 'uploads/package_invoice/');
                    $this->packageInvoiceRepository->uploadInvoices($path, $request->package_id);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Package invoices uploaded successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getPackagePhotos(Request $request)
    {
        try {
            $packageFiles = $this->packageFileRepository->getPackageFiles($request->package_id);
            return response()->json(['message' => 'Photos fetched successfully', 'data' => $packageFiles], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function setSpecialRequest(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = [
                'id' => $request->package_id,
                'special_request' => $request->special_request,
            ];
            $this->packageRepository->store($data);
            DB::commit();
            return response()->json(['message' => 'Special request added successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Photos fetched successfully'], 500);
        }
    }

    public function calculateEstimatedShipment(Request $request)
    {
        try {
            $estimatedAmount = 0;
            if (count($request->package_id) > 0) {
                $preference = $this->shipPreferencesRepository->getShippingPreference(Auth::id());
                $shippingPreferenceOption = json_decode($preference->shipping_preference_option);
                $shippingPackingOption = json_decode($preference->packing_option);
                $weight = $this->packageRepository->sumWeightPackageByIds($request->package_id);
                if ($preference && $preference->international_shipping_option == InternationalShippingOptions::DHL_EXPRESS) {
                    $estimatedAmount += $this->shipRepository->getShipPriceByWightAndService($weight, InternationalShippingOptions::DHL_NAME)->price;
                }
                if ($preference && $preference->international_shipping_option == InternationalShippingOptions::FEDEX_ECONOMY) {
                    $estimatedAmount += $this->shipRepository->getShipPriceByWightAndService($weight, InternationalShippingOptions::FEDEX_NAME)->price;
                }
                if ($preference && $preference->international_shipping_option == InternationalShippingOptions::SEA_FREIGHT) {
                    $estimatedAmount += $this->shipRepository->getShipPriceByVolumeAndService($weight, InternationalShippingOptions::SEA_FREIGHT_NAME);
                }
                if ($preference && $preference->international_shipping_option == InternationalShippingOptions::AIR_CARGO) {
                    $estimatedAmount += $this->shipRepository->getShipPriceByVolumeAndService($weight, InternationalShippingOptions::AIR_CARGO_NAME);
                }
                if ($preference && count($shippingPreferenceOption) > 0) {
                    $estimatedAmount += $this->shipPreferencesRepository->sumShippingPreferenceOption($shippingPreferenceOption);
                }
                if ($preference && count($shippingPackingOption) > 0) {
                    $estimatedAmount += $this->shipPreferencesRepository->sumPackingOption($shippingPackingOption);
                }
                $estimatedAmount += 10;
            }
            return response()->json(['message' => 'Estimated amount.', 'amount' => $estimatedAmount], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
