<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'property_id'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
