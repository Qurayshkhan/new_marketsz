<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\InternationalShippingOptions;
use App\Repositories\PackageRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\ShippingPreferencesRepository;
use App\Repositories\ShipRepository;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Str;

class ShipController extends Controller
{
    protected $shipRepository, $packageRepository, $paymentMethodRepository, $shippingPreferenceRepository;

    public function __construct(ShipRepository $shipRepository, PackageRepository $packageRepository, PaymentMethodRepository $paymentMethodRepository, ShippingPreferencesRepository $shippingPreferenceRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->shipRepository = $shipRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->shippingPreferenceRepository = $shippingPreferenceRepository;
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
            'internationalShippingMethod' => $this->shippingPreferenceRepository->getInternationalShippingOptions(),
            'userPreferences' => $this->shippingPreferenceRepository->getShippingPreference(auth()->id()),
            'packingOptions' => $this->shippingPreferenceRepository->getPackingOption(),
            'shippingPreferenceOptions' => $this->shippingPreferenceRepository->shippingPreferenceOptions(),
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

    public function deletePackageFromShip($id, $packageId)
    {
        try {
            DB::beginTransaction();

            $ship = $this->shipRepository->findById($id);
            if (!$ship) {
                return Redirect::back()->withErrors(['message' => 'Shipment not found.']);
            }

            $package = $this->packageRepository->findById($packageId);
            if (!$package) {
                return Redirect::back()->withErrors(['message' => 'Package not found.']);
            }
            if (!$ship->packages()->wherePivot('package_id', $packageId)->exists()) {
                return Redirect::back()->withErrors(['message' => 'Package is not attached to this shipment.']);
            }


            $ship->packages()->detach($packageId);

            $ship->total_weight -= $package->weight;
            $ship->total_price -= $package->total_value;

            $ship->total_weight = max($ship->total_weight, 0);
            $ship->total_price = max($ship->total_price, 0);

            if ($ship->packages()->count() === 0) {
                $ship->delete();
                DB::commit();
                return Redirect::route('customer.dashboard')->with('alert', 'Shipment deleted as no packages remain.');
            }
            $ship->save();

            DB::commit();
            return Redirect::back()->with('alert', 'Package removed from shipment successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => 'Error removing package: ' . $e->getMessage()]);
        }
    }

    public function calculateShippingCost(Request $request)
    {
        $internationalShippingAmount = 0.00; // Placeholder for actual shipping cost calculation logic
        if ($request->input('shipMethod') == InternationalShippingOptions::DHL_EXPRESS) {
            $shipPricing = $this->shipRepository->getShipPriceByWightAndService($request->input('shipWeight'), InternationalShippingOptions::DHL_NAME);
            $internationalShippingAmount += $shipPricing ? $shipPricing->price : 0.00;
        } else if ($request->input('shipMethod') == InternationalShippingOptions::FEDEX_ECONOMY) {
            $shipPricing = $this->shipRepository->getShipPriceByWightAndService($request->input('shipWeight'), InternationalShippingOptions::FEDEX_NAME);
            $internationalShippingAmount += $shipPricing ? $shipPricing->price : 0.00;
        } else if ($request->input('shipMethod') == InternationalShippingOptions::SEA_FREIGHT) {
            $shipPricing = $this->shipRepository->getShipPriceByVolumeAndService($request->input('shipWeight'), InternationalShippingOptions::SEA_FREIGHT_NAME);
            $internationalShippingAmount += $shipPricing ? $shipPricing->price : 0.00;
        }

        // Perform calculations based on the data
        // Example: $shippingCost = someCalculationFunction($data);

        return response()->json([
            'success' => true,
            'message' => 'Shipping cost calculated successfully.',
            'data' => [
                'international_shipping_amount' => $internationalShippingAmount ?? 0.00,
            ]
        ], 200);

    }
}
