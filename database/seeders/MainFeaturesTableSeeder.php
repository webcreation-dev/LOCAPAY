<?php

namespace Database\Seeders;

use App\Models\MainFeature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainFeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MainFeature::create([
            'name' => 'Sanitaire',
        ]);

        MainFeature::create([
            'name' => 'Avec douche',
        ]);

        MainFeature::create([
            'name' => 'Avec arrière-cour',
        ]);
    }
}
