<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Wallet extends Model {
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'wallets';

    protected $fillable = [
        'name', 'totalAllotment', 'totalSpent', 'user'
    ];
}
