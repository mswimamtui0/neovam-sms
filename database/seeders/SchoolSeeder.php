<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        School::firstOrCreate(
            ['code' => 'NEO001'],
            [
                'name'          => 'NEOVAM Demo School',
                'phone'         => '+255712345678',
                'email'         => 'info@neovam.co.tz',
                'address'       => 'Dar es Salaam, Tanzania',
                'has_primary'   => true,
                'has_secondary' => true,
                'has_alevel'    => true,
            ]
        );
    }
}