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
        'user_address_id',
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

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class, 'user_address_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function internationalShipping()
    {
        return $this->belongsTo(InternationalShippingOptions::class, 'international_shipping_option_id', 'id');
    }
}
