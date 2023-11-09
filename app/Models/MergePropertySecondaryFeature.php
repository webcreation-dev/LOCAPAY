<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MergePropertySecondaryFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'secondary_feature_id',
    ];

    public function property() {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function secondaryFeature() {
        return $this->belongsTo(SecondaryFeature::class, 'secondary_feature_id');
    }
}
