<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'user_id',
        'amount',
        'type',
        'transaction_id',
        'reason',
    ];

    public function contract() {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
