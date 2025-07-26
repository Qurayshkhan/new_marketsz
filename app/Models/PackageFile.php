<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFile extends Model
{
    protected $fillable = [
        'package_id',
        'file'
    ];
    protected $appends = ['file_with_url'];

    public function getFileWithUrlAttribute()
    {
        return asset($this->file);
    }
}
