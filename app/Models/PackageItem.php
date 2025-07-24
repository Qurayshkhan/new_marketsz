<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageItem extends Model
{
    protected $fillable = [
        'package_id',
        'title',
        'description',
        'item_note',
        'quantity',
        'value_per_unit',
        'total_line_value',
        'total_line_weight',
    ];
}
