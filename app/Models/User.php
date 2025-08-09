<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    const USER_TYPE_ADMIN = 1;
    const USER_TYPE_CUSTOMER = 2;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'country',
        'tax_id',
        'suite',
        'date_of_birth',
        'password',
        'is_active',
        'is_old',
        'user_name',
        'stripe_id',
        'type',
        'avatar',
        'state',
        'zip_code',
        'stripe_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected $appends = ['name', 'active'];
    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function getActiveAttribute()
    {
        return $this->is_active === 1 ? 'Active' : 'In Active';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeCustomer($query)
    {
        return $query->where('type', User::USER_TYPE_CUSTOMER);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    /**
     * Get the user's default US address
     */
    public function defaultUsAddress()
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'id')
            ->where('is_default_us', true);
    }

    /**
     * Get the user's default UK address
     */
    public function defaultUkAddress()
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'id')
            ->where('is_default_uk', true);
    }

    /**
     * Get all default addresses for the user
     */
    public function defaultAddresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id')
            ->where(function ($query) {
                $query->where('is_default_us', true)
                    ->orWhere('is_default_uk', true);
            });
    }
}
