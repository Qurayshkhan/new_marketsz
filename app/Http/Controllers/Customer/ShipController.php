<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\InternationalShippingOptions;
use App\Models\Transaction;
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

            $this->shipRepository->deletePendingShipment(Auth::id());

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

            // Validate required fields
            $request->validate([
                'id' => 'required|integer',
                'card_id' => 'required|integer',
                'estimated_shipping_charges' => 'required|numeric|min:0.01|max:999999.99',
                'user_address_id' => 'required|integer',
            ]);

            $ship = $this->shipRepository->findById($request->input('id'));
            if (!$ship) {
                return Redirect::back()->withErrors(['message' => 'Shipment not found.']);
            }

            // Verify the shipment belongs to the authenticated user
            if ($ship->user_id !== Auth::id()) {
                return Redirect::back()->withErrors(['message' => 'Unauthorized access to shipment.']);
            }
            // Prepare data for update, ensuring JSON fields are properly handled
            $updateData = [
                'estimated_shipping_charges' => $request->input('estimated_shipping_charges'),
                'subtotal' => $request->input('subtotal'),
                'user_address_id' => $request->input('user_address_id'),
            ];

            // Handle JSON fields - ensure they are in the correct format for JSON columns
            // Laravel's JSON casting will automatically encode arrays/objects to JSON strings
            if ($request->has('international_shipping_option_id')) {
                $intShipping = $request->input('international_shipping_option_id');
                // If it's a string, try to decode it; if it's a number, convert to array; otherwise use as-is
                if (is_string($intShipping)) {
                    $decoded = json_decode($intShipping, true);
                    $updateData['international_shipping_option_id'] = $decoded !== null ? $decoded : $intShipping;
                } elseif (is_numeric($intShipping)) {
                    // Convert single number to array for consistency
                    $updateData['international_shipping_option_id'] = [$intShipping];
                } else {
                    $updateData['international_shipping_option_id'] = $intShipping;
                }
            }

            if ($request->has('packing_option_id')) {
                $packing = $request->input('packing_option_id');
                // Ensure it's an array
                if (is_string($packing)) {
                    $decoded = json_decode($packing, true);
                    $updateData['packing_option_id'] = $decoded !== null ? $decoded : (is_numeric($packing) ? [$packing] : $packing);
                } elseif (is_numeric($packing)) {
                    $updateData['packing_option_id'] = [$packing];
                } else {
                    $updateData['packing_option_id'] = is_array($packing) ? $packing : [$packing];
                }
            }

            if ($request->has('shipping_preference_option_id')) {
                $preference = $request->input('shipping_preference_option_id');
                // Ensure it's an array
                if (is_string($preference)) {
                    $decoded = json_decode($preference, true);
                    $updateData['shipping_preference_option_id'] = $decoded !== null ? $decoded : (is_numeric($preference) ? [$preference] : $preference);
                } elseif (is_numeric($preference)) {
                    $updateData['shipping_preference_option_id'] = [$preference];
                } else {
                    $updateData['shipping_preference_option_id'] = is_array($preference) ? $preference : [$preference];
                }
            }

            $this->shipRepository->update($ship, $updateData);

            $user = Auth::user();
            $stripeCharge = null;
            $card = $this->paymentMethodRepository->findById($request->input('card_id'));

            // Verify the card belongs to the authenticated user
            if (!$card || $card->user_id !== $user->id) {
                throw new \Exception('Invalid payment method selected.');
            }

            // Verify user has a Stripe customer ID
            if (empty($user->stripe_id)) {
                throw new \Exception('Payment method not properly configured. Please add a payment method first.');
            }

            if ($card) {
                $customerId = $user->stripe_id;
                // Convert amount to cents and ensure it's an integer
                $originalAmount = $request->input('estimated_shipping_charges');
                $amountInCents = (int) round($originalAmount * 100);

                \Log::info('Payment amount conversion', [
                    'original_amount' => $originalAmount,
                    'amount_in_cents' => $amountInCents,
                    'user_id' => $user->id,
                    'ship_id' => $ship->id
                ]);

                // Validate the converted amount
                if ($amountInCents <= 0) {
                    throw new \Exception('Invalid payment amount. Amount must be greater than zero.');
                }

                $stripeCharge = $this->stripeClient->createCharge([
                    'customer' => $customerId,
                    'source' => $card->card_id,
                    'receipt_email' => $user->email,
                    'amount' => $amountInCents,
                    'currency' => 'USD',
                    'capture' => true,
                    'description' => "Payment by {$user->name} to create shipment.",
                    'metadata' => [
                        'user_id' => $user->id,
                        'order_ref' => uniqid('ship_'),
                    ],
                ]);

                // Check if the charge creation was successful
                if (isset($stripeCharge['error'])) {
                    \Log::error('Stripe charge creation failed', [
                        'error' => $stripeCharge['error'],
                        'amount_in_cents' => $amountInCents,
                        'original_amount' => $originalAmount,
                        'user_id' => $user->id,
                        'ship_id' => $ship->id
                    ]);
                    throw new \Exception('Payment failed: ' . $stripeCharge['error']);
                }

                // Verify the charge was successful
                if (!$stripeCharge->paid) {
                    throw new \Exception('Payment was not successful. Please try again.');
                }

                $this->transactionRepository->create([
                    'user_id' => $user->id,
                    'status' => $stripeCharge->paid ? Transaction::STATUS_SUCCESS : Transaction::STATUS_CANCELED,
                    'transaction_id' => $stripeCharge->id,
                    'description' => $stripeCharge->description,
                    'amount' => $stripeCharge->amount / 100,
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
            \Log::error('Checkout failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'ship_id' => $request->input('id'),
                'card_id' => $request->input('card_id'),
                'trace' => $e->getTraceAsString()
            ]);
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
