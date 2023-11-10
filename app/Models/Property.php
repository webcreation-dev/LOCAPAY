<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'property_last_name',
        'property_first_name',
        'property_location',
        'monthly_rent',
        'description',
        'main_image',
        'owner_phone',
        'status',
        'rating',
        'general_rating',
        'team_rating',
        'user_id',
        'city_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gallery() {
        return $this->hasMany(PropertyGallery::class, 'property_id');
    }

    public function contracts() {
        return $this->hasMany(Contract::class, 'property_id');
    }

    public function mainFeatures() {
        return $this->belongsToMany(MainFeature::class, 'merge_property_main_features', 'property_id', 'main_feature_id');
    }

    public function secondaryFeatures() {
        return $this->belongsToMany(SecondaryFeature::class, 'merge_property_secondary_features', 'property_id', 'secondary_feature_id');
    }
}
