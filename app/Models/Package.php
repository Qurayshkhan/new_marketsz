<?php

namespace App\Models;

use App\Helpers\PackageStatus;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['package_id', 'tracking_id', 'sender_id', 'special_request', 'date_received', 'from', 'total_value', 'weight'];
    protected $appends = ['status_name'];
    public function items()
    {
        return $this->hasMany(PackageItem::class, 'package_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(PackageFile::class, 'package_id', 'id');
    }

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case 1:
                return "Action Required";
            case 2:
                return "In Review";
            case 3:
                return "Ready to Send";
            case 4:
                return "Consolidate";
            default:
                return "Invalid status code";
        }
    }
}
