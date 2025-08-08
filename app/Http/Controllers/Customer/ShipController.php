<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\InternationalShippingOptions;
use App\Payments\Stripe;
use App\Repositories\PackageRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\ShippingPreferencesRepository;
use App\Repositories\ShipRepository;
use App\Repositories\TransactionRepository;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Str;

class ShipController extends Controller
{
    protected $shipRepository, $packageRepository, $paymentMethodRepository, $shippingPreferenceRepository, $stripeClient, $transactionRepository;

    public function __construct(ShipRepository $shipRepository, PackageRepository $packageRepository, PaymentMethodRepository $paymentMethodRepository, ShippingPreferencesRepository $shippingPreferenceRepository, TransactionRepository $transactionRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->shipRepository = $shipRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->shippingPreferenceRepository = $shippingPreferenceRepository;
        $this->transactionRepository = $transactionRepository;
        $this->stripeClient = new Stripe();
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
        $internationalShippingAmount = 0.00;
        $packingOptionAmount = 0.00;
        $shippingPreferenceOptionAmount = 0.00;
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

        if ($request->input('packingOption')) {
            $packingOptionAmount += $this->shippingPreferenceRepository->sumPackingOption($request->input('packingOption'));
        }

        if ($request->input('shippingPreferenceOption')) {
            $shippingPreferenceOptionAmount += $this->shippingPreferenceRepository->sumShippingPreferenceOption($request->input('shippingPreferenceOption'));
        }


        // Perform calculations based on the data
        // Example: $shippingCost = someCalculationFunction($data);

        return response()->json([
            'success' => true,
            'message' => 'Shipping cost calculated successfully.',
            'data' => [
                'international_shipping_amount' => $internationalShippingAmount ?? 0.00,
                'packing_option_amount' => $packingOptionAmount ?? 0.00,
                'shipping_preference_option_amount' => $shippingPreferenceOptionAmount ?? 0.00,
            ]
        ], 200);
    }

    public function addNationalId(Request $request, $id)
    {
        $request->validate([
            'national_id' => 'required|string|max:255',
        ]);

        try {
            $ship = $this->shipRepository->findById($id);
            if (!$ship) {
                return Redirect::back()->withErrors(['message' => 'Shipment not found.']);
            }
            DB::beginTransaction();
            $ship->national_id = $request->input('national_id');
            $ship->save();

            DB::commit();
            return Redirect::back()->with('alert', 'National ID added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => 'Error adding national ID: ' . $e->getMessage()]);
        }
    }

    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();
            $ship = $this->shipRepository->findById($request->input('id'));
            if (!$ship) {
                return Redirect::back()->withErrors(['message' => 'Shipment not found.']);
            }
            $this->shipRepository->update($ship, [
                'international_shipping_option_id' => $request->input('international_shipping_option_id'),
                'packing_option_id' => json_encode($request->input('packing_option_id')),
                'shipping_preference_option_id' => json_encode($request->input('shipping_preference_option_id')),
                'estimated_shipping_charges' => $request->input('estimated_shipping_charges'),
                'subtotal' => $request->input('subtotal'),
                'user_address_id' => $request->input('user_address_id'),
            ]);

            $user = Auth::user();
            $stripeCharge = null;
            $card = $this->paymentMethodRepository->findById($request->input('card_id'));
            if ($card) {
                $customerId = $user->stripe_id;
                $stripeCharge = $this->stripeClient->createCharge([
                    'customer' => $customerId,
                    'source' => $card->card_id,
                    'receipt_email' => $user->email,
                    'amount' => $request->input('estimated_shipping_charges') * 100,
                    'currency' => 'USD',
                    'capture' => true,
                    'description' => "Payment by {$user->name} to create shipment.",
                    'metadata' => [],
                ]);
                $this->transactionRepository->create([
                    'user_id' => $user->id,
                    'status' => $stripeCharge->paid,
                    'transaction_id' => $stripeCharge->id,
                    'description' => $stripeCharge->description,
                    'card' => $stripeCharge->source->last4,
                    'transaction_date' => Carbon::createFromTimestamp($stripeCharge->created)->toDateTimeString(),
                ]);
            }
            foreach ($ship->packages as $package) {
                $package->status = \App\Helpers\PackageStatus::CONSOLIDATE;
                $package->save();
            }
            $ship->invoice_status = 'paid';
            $ship->save();
            DB::commit();

            return Redirect::route('customer.shipment.success', ['shipId' => $ship->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => 'Error during checkout: ' . $e->getMessage()]);
        }
    }

    public function successPage($shipId)
    {
        $shipment = $this->shipRepository->findById($shipId);
        $shipment->load('userAddress', 'user');
        return Inertia::render('Customers/Shipment/SuccessPage', [
            'shipment' => $shipment,
        ]);
    }

    public function myShipments()
    {
        $shipments = $this->shipRepository->getShipsByUserId(Auth::id());
        return Inertia::render('Customers/Shipment/MyShipment', ['shipments' => $shipments]);
    }

    public function viewShipment($ship)
    {
        $details = $this->shipRepository->getShipDetails($ship);
        $packingOptions = $this->shippingPreferenceRepository->getPackingOptionByIds(json_decode($details->packing_option_id));
        $shippingPreferenceOption = $this->shippingPreferenceRepository->shippingPreferenceOptionByIds(json_decode($details->shipping_preference_option_id));
        return Inertia::render('Customers/Shipment/Detail', [
            'shipDetails' => $details,
            'packingOptions' => $packingOptions,
            'shippingPreferenceOption' => $shippingPreferenceOption,
        ]);
    }

}
