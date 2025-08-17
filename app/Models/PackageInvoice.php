<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageInvoice extends Model
{
    protected $fillable = ['package_id', 'image'];

    protected $appends = ['invoice_path_url'];

    public function getInvoicePathUrlAttribute()
    {
        return asset($this->image);
    }
}
