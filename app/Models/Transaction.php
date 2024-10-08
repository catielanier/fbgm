<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Transaction extends Model {
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'transactions';

    protected $fillable = [
        'recipient', 'amount', 'date', 'category', 'user'
    ];
}
