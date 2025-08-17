<?php

namespace App\Http\Controllers;

use App\Repositories\ShipRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShipmentController extends Controller
{
    protected $shipRepository;

    public function __construct(ShipRepository $shipRepository)
    {
        $this->shipRepository = $shipRepository;
    }
    public function index()
    {
        $shipments = $this->shipRepository->getShipments();
        return Inertia::render('Admin/Shipments/Report', ['shipments' => $shipments]);
    }
}
