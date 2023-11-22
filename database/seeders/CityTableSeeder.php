<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::create([
            'name' => 'Akpakpa',
        ]);

        City::create([
            'name' => 'Cadjehoun',
        ]);

        City::create([
            'name' => 'Gbegamey',
        ]);

        City::create([
            'name' => 'Houeyiho',
        ]);

        City::create([
            'name' => 'Fidjrossè',
        ]);

        City::create([
            'name' => 'Agla',
        ]);

        City::create([
            'name' => 'Godomey',
        ]);

        City::create([
            'name' => 'Tanpkè',
        ]);

        City::create([
            'name' => 'Houeto',
        ]);

        City::create([
            'name' => 'Aitchedji',
        ]);

    }
}
