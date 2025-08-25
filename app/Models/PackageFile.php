<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFile extends Model
{
    protected $fillable = [
        'package_id',
        'package_item_id',
        'name',
        'file'
    ];

    protected $appends = ['file_with_url'];

    public function packageItem()
    {
        return $this->belongsTo(PackageItem::class, 'package_item_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function getFileWithUrlAttribute()
    {
        return asset($this->file);
    }
}
