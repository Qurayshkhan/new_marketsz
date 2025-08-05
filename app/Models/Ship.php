<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_number',
        'status',
        'total_weight',
        'total_price',
        'total_ship_payment',
        'address_id',
        'international_shipping_option_id',
        'packing_option_id',
        'shipping_preference_option_id',
        'national_id',
        'handling_fee',
        'subtotal',
        'package_level_charges',
        'estimated_shipping_charges',
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'ship_packages', 'ship_id', 'package_id');
    }
}
