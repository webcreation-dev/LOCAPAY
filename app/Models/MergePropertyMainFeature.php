<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MergePropertyMainFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'main_feature_id',
    ];

    public function property() {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function mainFeature() {
        return $this->belongsTo(MainFeature::class, 'main_feature_id');
    }
}
