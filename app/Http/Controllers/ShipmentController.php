<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateShipmentRequest;
use App\Models\Ship;
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
}
