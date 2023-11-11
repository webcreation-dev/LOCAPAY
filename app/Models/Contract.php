<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'beneficiary_id',
        'landlord_id',
        'document',
        'amount',
        'start_date',
        'type',
        'status',
        'observations',
    ];

    public function beneficiary() {
        return $this->belongsTo(User::class, 'beneficiary_id');
    }

    public function landlord() {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'contract_id');
    }

    public function schedule() {
        return $this->hasMany(Schedule::class, 'contract_id');
    }
}
