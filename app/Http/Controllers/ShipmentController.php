<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateShipmentRequest;
use App\Models\Ship;
use App\Models\PackingOptions;
use App\Models\ShippingPreferenceOption;
use App\Repositories\ShipRepository;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class ShipmentController extends Controller
{
    protected $shipRepository;

    public function __construct(ShipRepository $shipRepository)
    {
        $this->shipRepository = $shipRepository;
    }
    public function index(Request $request)
    {
        $shipments = $this->shipRepository->getShipments($request);

        // Process shipments to add additional services information
        $shipments->getCollection()->transform(function ($shipment) {
            // Get packing options
            if ($shipment->packing_option_id) {
                $packingOptionIds = is_string($shipment->packing_option_id)
                    ? json_decode($shipment->packing_option_id, true)
                    : $shipment->packing_option_id;
                if (is_array($packingOptionIds)) {
                    $shipment->packing_options = PackingOptions::whereIn('id', $packingOptionIds)->get();
                }
            }

            // Get shipping preference options
            if ($shipment->shipping_preference_option_id) {
                $shippingPreferenceOptionIds = is_string($shipment->shipping_preference_option_id)
                    ? json_decode($shipment->shipping_preference_option_id, true)
                    : $shipment->shipping_preference_option_id;
                if (is_array($shippingPreferenceOptionIds)) {
                    $shipment->shipping_preference_options = ShippingPreferenceOption::whereIn('id', $shippingPreferenceOptionIds)->get();
                }
            }

            return $shipment;
        });

        return Inertia::render('Admin/Shipments/Report', ['shipments' => $shipments]);
    }

    public function outbondRequests(Request $request){
        $shipments = $this->shipRepository->getShipments($request);
        return Inertia::render('Admin/Shipments/OutbondShipRequest/Report', ['shipments' => $shipments]);
    }

    public function edit(Ship $ship)
    {
        $ship->load('packages', 'user', 'userAddress', 'internationalShipping');
        return Inertia::render('Admin/Shipments/EditTabs/Basic', ['ship' => $ship]);
    }

    public function update(UpdateShipmentRequest $request, Ship $ship)
    {
        try {
            DB::beginTransaction();
            $this->shipRepository->update($ship, $request->all());
            DB::commit();
            return Redirect::back()->with('alert', 'Shipment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function shipPackages(Ship $ship)
    {
        try {
            $ship->load('packages.items', 'packages.files', 'user');
            return Inertia::render('Admin/Shipments/EditTabs/Packages', ['ship' => $ship]);
        } catch (\Exception $e) {
            return Redirect::back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, Ship $ship)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,shipped,delivered,cancelled'
            ]);

            DB::beginTransaction();
            $ship->update(['status' => $request->status]);
            DB::commit();

            return Redirect::back()->with('alert', 'Shipment status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
