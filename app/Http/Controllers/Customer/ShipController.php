<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Repositories\PackageRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\ShipRepository;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Str;

class ShipController extends Controller
{
    protected $shipRepository, $packageRepository, $paymentMethodRepository;

    public function __construct(ShipRepository $shipRepository, PackageRepository $packageRepository, PaymentMethodRepository $paymentMethodRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->shipRepository = $shipRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
    }


    public function index($ship)
    {
        // dd($request->all());
        $shipId = Crypt::decrypt($ship);
        $ship = $this->shipRepository->findById($shipId);
        $ship->load('packages');
        $cards = $this->paymentMethodRepository->getCardsByUser(auth()->id());
        return Inertia::render('Customers/Shipment/Create', [
            'ship' => $ship,
            'cards' => $cards,
            'publishableKey' => env('STRIPE_KEY'),
            'userAddresses' => auth()->user()->addresses,
            // 'internationalShippingMethod' =>
        ]);
    }

    public function createShipment(Request $request)
    {
        try {
            DB::beginTransaction();
            $totalPackageWeight = 0;
            $totalPackagePrice = 0;
            $packages = $this->packageRepository->getPackageByIds($request->input('package_ids', []));
            if (!$packages->isEmpty()) {
                foreach ($packages as $package) {
                    $totalPackageWeight += $package->weight;
                    $totalPackagePrice += $package->total_value;
                }
            } else {
                return Redirect::back()->withErrors(['message' => 'No packages selected for shipment.']);
            }
            $ship = $this->shipRepository->create([
                'user_id' => auth()->id(),
                'tracking_number' => rand(00000000, 99999999),
                'total_weight' => $totalPackageWeight,
                'total_price' => $totalPackagePrice,
            ]);

            $ship->packages()->attach($packages->pluck('id'));
            DB::commit();
            return Redirect::route('customer.shipment.index', ['ship' => Crypt::encrypt($ship->id)])->with('success', 'Shipment created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => 'Error creating shipment: ' . $e->getMessage()]);
        }

    }
}
