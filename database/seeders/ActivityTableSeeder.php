<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Notifications\Action;

class ActivityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Activity::create([
            'name' => 'Maçon',
        ]);

        Activity::create([
            'name' => 'Soudeur',
        ]);

        Activity::create([
            'name' => 'Électricien',
        ]);

        Activity::create([
            'name' => 'Entretien',
        ]);

        Activity::create([
            'name' => 'Lavandière',
        ]);
    }
}
