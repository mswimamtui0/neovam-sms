<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
 public function run(): void
 {
 $this->call([
 RoleSeeder::class,
 SchoolSeeder::class,
 LevelSeeder::class,
 SubjectSeeder::class,
 DemoUsersSeeder::class,
 SmsTemplateSeeder::class,
 ]);
 }
}