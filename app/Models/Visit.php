<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'status',
        'visit_date',
        'price',
        'user_id'
    ];

    const STATUS = [
        'in_progress',
        'visited',
        'cancelled'
    ];

    public function propertie()
    {
        return $this->belongsTo(Property::class);
    }
}
