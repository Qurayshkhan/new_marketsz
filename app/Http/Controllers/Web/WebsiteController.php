<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ShippingPricing;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('home');
    }
    public function calculator()
    {
        return view('calculator');
    }
    public function contact()
    {
        return view('contact');
    }
    public function about()
    {
        return view('about');
    }

    public function faqs()
    {
        return view('faqs');
    }

    public function terms()
    {
        return view('terms');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function calculate(Request $request)
    {
        $length = $request->length;
        $width = $request->width;
        $height = $request->height;
        $unit = $request->dimension_unit;
        $weight = $request->weight;
        $weightUnit = $request->weight_unit;
        $value = $request->value;

        // Convert dimensions
        if ($unit === 'cm') {
            $length *= 0.393701;
            $width *= 0.393701;
            $height *= 0.393701;
        }
        // Convert weight
        if ($weightUnit === 'kg') {
            $weight *= 2.20462;
        }

        $linear = $length + $width + $height;
        $volumeCubicInches = $length * $width * $height;
        $volumeCubicFeet = $volumeCubicInches / 1728;

        $results = [];

        // Get all pricing rows
        $services = ShippingPricing::all();

        foreach ($services as $service) {
            $price = null;

            if ($service->type === 'Weight') {
                if ($linear > 72) {
                    $price = $volumeCubicFeet * $service->price;
                } else {
                    $price = $weight * $service->price;
                }
            }

            if ($service->type === 'Volume') {
                if (
                    $volumeCubicFeet >= $service->range_value &&
                    ($service->range_to === null || $volumeCubicFeet <= $service->range_to)
                ) {
                    $price = $volumeCubicFeet * $service->price;
                }
            }

            if ($price !== null) {
                $results[] = [
                    "carrier" => $service->service,
                    "transit_time" => $this->getTransitTime($service->service),
                    "rate" => round($price, 2),
                    "currency" => "USD"
                ];
            }
        }

        // Get cheapest per carrier
        $bestRates = collect($results)
            ->groupBy('carrier')
            ->map(function ($items) {
                return $items->sortBy('rate')->first();
            })
            ->values()
            ->toArray();

        // Find overall best price
        $overallBest = collect($bestRates)->sortBy('rate')->first();

        return response()->json([
            'best_price' => $overallBest,
            'best_estimates' => $bestRates,
            'note' => 'This is only an estimate. Final charges may vary.'
        ]);
    }


    private function getTransitTime($service)
    {
        return match ($service) {
            'FedEx Economy' => '5 - 10 days',
            'DHL' => '1 - 4 days',
            'Seafreight' => '15 - 30 days',
            'Aircargo' => '3 - 7 days',
            default => 'Varies'
        };
    }

}
