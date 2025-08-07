<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'status', 'card', 'amount', 'description', 'transaction_id', 'transaction_date'];
}
