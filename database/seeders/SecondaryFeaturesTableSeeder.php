<?php

namespace Database\Seeders;

use App\Models\SecondaryFeature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SecondaryFeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SecondaryFeature::create([
            'name' => 'Cuisine',
        ]);

        SecondaryFeature::create([
            'name' => 'Salle à manger',
        ]);

        SecondaryFeature::create([
            'name' => 'Salon',
        ]);
    }
}
