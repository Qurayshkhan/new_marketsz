<?php

namespace App\Models;

use App\Helpers\PackageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
            case PackageStatus::ACTION_REQUIRED:
                return "Action Required";
            case PackageStatus::IN_REVIEW:
                return "In Review";
            case PackageStatus::READY_TO_SEND:
                return "Ready to Send";
            case PackageStatus::CONSOLIDATE:
                return "Consolidate";
            default:
                return "Invalid status code";
        }
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }
}
