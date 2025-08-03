<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'address_name',
        'full_name',
        'address_line_1',
        'address_line_2',
        'country',
        'state',
        'city',
        'postal_code',
        'country_code',
        'phone_number',
        'is_default_us',
        'is_default_uk',
    ];

    protected $casts = [
        'is_default_us' => 'boolean',
        'is_default_uk' => 'boolean',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get default US addresses
     */
    public function scopeDefaultUs($query)
    {
        return $query->where('is_default_us', true);
    }

    /**
     * Scope to get default UK addresses
     */
    public function scopeDefaultUk($query)
    {
        return $query->where('is_default_uk', true);
    }

    /**
     * Get full address as a formatted string
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ];

        return implode(', ', array_filter($parts));
    }

    /**
     * Check if this address is a default for any type
     */
    public function isDefault(): bool
    {
        return $this->is_default_us || $this->is_default_uk;
    }

    /**
     * Get the default types for this address
     */
    public function getDefaultTypes(): array
    {
        $types = [];

        if ($this->is_default_us) {
            $types[] = 'us';
        }

        if ($this->is_default_uk) {
            $types[] = 'uk';
        }

        return $types;
    }

    /**
     * Get the default type display text
     */
    public function getDefaultTypeText(): string
    {
        $types = $this->getDefaultTypes();

        if (empty($types)) {
            return '';
        }

        if (count($types) === 2) {
            return 'US & UK';
        }

        return strtoupper($types[0]);
    }
}
