<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\PackageStatus;
use App\Http\Controllers\Controller;
use App\Repositories\PackageFileRepository;
use App\Repositories\PackageInvoiceRepository;
use App\Repositories\PackageRepository;
use App\Traits\CommonTrait;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class SuiteController extends Controller
{
    use CommonTrait;
    protected $packageRepository, $packageInvoiceRepository, $packageFileRepository;
    public function __construct(PackageRepository $packageRepository, PackageInvoiceRepository $packageInvoiceRepository, PackageFileRepository $packageFileRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->packageInvoiceRepository = $packageInvoiceRepository;
        $this->packageFileRepository = $packageFileRepository;
    }
    public function index()
    {
        return redirect()->route('customer.suiteActionRequired');
    }

    public function actionRequired()
    {
        return Inertia::render('Customers/Suite/SuitTabs/ActionRequired', [
            'actions' => $this->packageRepository->shipmentPackages(Auth::id()),
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
}
