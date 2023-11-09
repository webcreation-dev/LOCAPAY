<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryFeature extends Model
{
    use HasFactory;

    public function properties() {
        return $this->belongsToMany(Property::class, 'merge_property_secondary_features', 'secondary_feature_id', 'property_id');
    }
}
