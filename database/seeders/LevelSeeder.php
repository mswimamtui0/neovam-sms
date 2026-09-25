<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\School;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
 public function run(): void
 {
 $school = School::first();
 if (!$school) return;

 $classes = [
 // Pre-Primary
 ['name' => 'Nursery', 'level' => 'nursery'],
 ['name' => 'KG', 'level' => 'kg'],
 ['name' => 'Pre-Unit', 'level' => 'pre_unit'],

 // Primary
 ['name' => 'Standard 1', 'level' => 'primary'],
 ['name' => 'Standard 2', 'level' => 'primary'],
 ['name' => 'Standard 3', 'level' => 'primary'],
 ['name' => 'Standard 4', 'level' => 'primary'],
 ['name' => 'Standard 5', 'level' => 'primary'],
 ['name' => 'Standard 6', 'level' => 'primary'],
 ['name' => 'Standard 7', 'level' => 'primary'],

 // Secondary
 ['name' => 'Form 1', 'level' => 'secondary'],
 ['name' => 'Form 2', 'level' => 'secondary'],
 ['name' => 'Form 3', 'level' => 'secondary'],
 ['name' => 'Form 4', 'level' => 'secondary'],

 // A-Level
 ['name' => 'Form 5', 'level' => 'alevel'],
 ['name' => 'Form 6', 'level' => 'alevel'],
 ];

 foreach ($classes as $c) {
 ClassRoom::firstOrCreate(
 ['school_id' => $school->id, 'name' => $c['name']],
 ['level' => $c['level'], 'stream' => 'A']
 );
 }
 }
}