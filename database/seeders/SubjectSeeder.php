<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
 public function run(): void
 {
 $school = School::first();
 if (!$school) return;

 $subjects = [
 ["Mathematics", "MATH", "nursery,kg,pre_unit,primary,secondary,alevel", "Core"],
 ["English", "ENG", "nursery,kg,pre_unit,primary,secondary,alevel", "Core"],
 ["Kiswahili", "KIS", "nursery,kg,pre_unit,primary,secondary,alevel", "Core"],
 ["Physics", "PHY", "secondary,alevel", "Science"],
 ["Chemistry", "CHEM", "secondary,alevel", "Science"],
 ["Biology", "BIO", "secondary,alevel", "Science"],
 ["Geography", "GEO", "primary,secondary,alevel", "Arts"],
 ["History", "HIST", "primary,secondary,alevel", "Arts"],
 ["Civics", "CIV", "primary,secondary,alevel", "Core"],
 ["ICT", "ICT", "primary,secondary,alevel", "Technical"],
 ["Bible Knowledge", "BK", "primary,secondary,alevel", "Religious"],
 ["Book Keeping", "BKEEP","secondary,alevel", "Commerce"],
 ];

 foreach ($subjects as $s) {
 Subject::firstOrCreate(
 ["code" => $s[1]],
 [
 "school_id" => $school->id,
 "name" => $s[0],
 "levels" => $s[2],
 "category" => $s[3],
 "is_active" => true,
 ]
 );
 }
 }
}