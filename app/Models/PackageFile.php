<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFile extends Model
{
    protected $fillable = [
        'package_id',
        'file'
    ];
}
