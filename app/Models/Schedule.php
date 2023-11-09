<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'transaction_id',
        'type',
        'month_of_rent',
        'amount_to_pay',
        'amount_paid',
        'remaining_to_pay',
        'status',
    ];

    public function contract() {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
