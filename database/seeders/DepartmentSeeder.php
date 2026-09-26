<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();

        // Teaching departments
        $teaching = [
            ["Science",        "science",    "blue",   "Physics, Chemistry, Biology, ICT"],
            ["Arts",           "arts",       "purple", "History, Geography, Civics, Literature"],
            ["Commerce",       "commerce",   "green",  "Bookkeeping, Commerce, Economics"],
            ["Technical",      "technical",  "orange", "ICT, Tech Drawing, Woodwork"],
            ["Languages",      "languages",  "indigo", "English, Kiswahili, French"],
            ["Mathematics",    "mathematics","teal",   "Pure Math, Additional Math, Statistics"],
            ["Religious",      "religious",  "yellow", "Bible Knowledge, Islamic Studies"],
        ];

        foreach ($teaching as $d) {
            Department::updateOrCreate(
                ["code" => $d[1]],
                [
                    "school_id"   => $school?->id,
                    "name"        => $d[0],
                    "type"        => "teaching",
                    "color"       => $d[2],
                    "description" => $d[3],
                    "is_active"   => true,
                ]
            );
        }

        // Non-teaching departments
        $nonTeaching = [
            ["Health",         "health",        "red",    "Sick bay, first aid, hygiene"],
            ["Discipline",     "discipline",    "orange", "Behavior and rules"],
            ["Sports",         "sports",        "green",  "Games, teams, competitions"],
            ["Boarding",       "boarding",      "purple", "Dormitory supervision"],
            ["Feeding",        "feeding",       "yellow", "Meals and kitchen"],
            ["Library",        "library",       "indigo", "Books and reading"],
            ["Guidance",       "guidance",      "teal",   "Counseling and careers"],
            ["Environment",    "environment",   "green",  "Cleanliness and grounds"],
            ["Security",       "security",      "gray",   "Gate, patrols, safety"],
            ["Finance",        "finance",       "blue",   "Fees, salaries, budget"],
            ["Administration", "administration","blue",   "Management and office"],
        ];

        foreach ($nonTeaching as $d) {
            Department::updateOrCreate(
                ["code" => $d[1]],
                [
                    "school_id"   => $school?->id,
                    "name"        => $d[0],
                    "type"        => "non_teaching",
                    "color"       => $d[2],
                    "description" => $d[3],
                    "is_active"   => true,
                ]
            );
        }

        // Link subjects to their departments
        $subjectMapping = [
            "science"    => ["Physics", "Chemistry", "Biology", "ICT"],
            "arts"       => ["History", "Geography", "Civics", "Bible Knowledge"],
            "commerce"   => ["Book Keeping", "Commerce", "Economics"],
            "languages"  => ["English", "Kiswahili"],
            "mathematics"=> ["Mathematics"],
        ];

        foreach ($subjectMapping as $deptCode => $subjectNames) {
            $dept = Department::where("code", $deptCode)->first();
            if (!$dept) continue;

            foreach ($subjectNames as $name) {
                Subject::where("name", $name)->update(["department_id" => $dept->id]);
            }
        }

        $this->command->info("Seeded " . count($teaching) . " teaching + " . count($nonTeaching) . " non-teaching departments.");
    }
}