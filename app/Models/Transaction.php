<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'qty', 'total_price', 'expired_at', 'status_id', 'product_id', 'user_id'
    ];
}
