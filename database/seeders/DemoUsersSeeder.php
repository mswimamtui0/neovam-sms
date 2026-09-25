<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        if (!$school) {
            $this->command->warn("No school found. Run SchoolSeeder first.");
            return;
        }



        
        $defaultPassword = Hash::make("Password123!");

        /* ============================================
           1. ADMIN
           ============================================ */
        $admin = User::updateOrCreate(
            ["email" => "admin@neovam.co.tz"],
            [
                "name"      => "System Admin",
                "password"  => $defaultPassword,
                "phone"     => "+255712345678",
                "school_id" => $school->id,
            ]
        );
        $admin->syncRoles(["admin"]);

        /* ============================================
           2. HEAD OF SCHOOL
           ============================================ */
        $head = User::updateOrCreate(
            ["email" => "head@neovam.co.tz"],
            [
                "name"      => "Head of School",
                "password"  => $defaultPassword,
                "phone"     => "+255712345679",
                "school_id" => $school->id,
            ]
        );
        $head->syncRoles(["head_of_school"]);

        /* ============================================
           3. ACADEMIC MASTER
           ============================================ */
        $academic = User::updateOrCreate(
            ["email" => "academic@neovam.co.tz"],
            [
                "name"      => "Academic Master",
                "password"  => $defaultPassword,
                "phone"     => "+255712345680",
                "school_id" => $school->id,
            ]
        );
        $academic->syncRoles(["academic_master"]);

        /* ============================================
           4. BURSAR
           ============================================ */
        $bursar = User::updateOrCreate(
            ["email" => "bursar@neovam.co.tz"],
            [
                "name"      => "Bursar",
                "password"  => $defaultPassword,
                "phone"     => "+255712345681",
                "school_id" => $school->id,
            ]
        );
        $bursar->syncRoles(["bursar"]);

        /* ============================================
           5. TEACHER
           ============================================ */
        $teacherUser = User::updateOrCreate(
            ["email" => "teacher@neovam.co.tz"],
            [
                "name"      => "Jane Teacher",
                "password"  => $defaultPassword,
                "phone"     => "+255712345682",
                "school_id" => $school->id,
            ]
        );
        $teacherUser->syncRoles(["teacher"]);

        $teacherStaff = Staff::updateOrCreate(
            ["staff_no" => "STF-TEACHER-001"],
            [
                "school_id"           => $school->id,
                "user_id"             => $teacherUser->id,
                "first_name"          => "Jane",
                "last_name"           => "Teacher",
                "gender"              => "female",
                "phone"               => "+255712345682",
                "email"               => "teacher@neovam.co.tz",
                "staff_type"          => "Teacher",
                "department"          => "Academic",
                "role_title"          => "Mathematics Teacher",
                "status"              => "active",
                "assigned_classrooms" => "4,5,6",
            ]
        );

        /* ============================================
           6. TEACHER ON DUTY
           ============================================ */
        $dutyUser = User::updateOrCreate(
            ["email" => "duty@neovam.co.tz"],
            [
                "name"      => "John Duty",
                "password"  => $defaultPassword,
                "phone"     => "+255712345683",
                "school_id" => $school->id,
            ]
        );
        $dutyUser->syncRoles(["teacher_on_duty"]);

        Staff::updateOrCreate(
            ["staff_no" => "STF-DUTY-001"],
            [
                "school_id"           => $school->id,
                "user_id"             => $dutyUser->id,
                "first_name"          => "John",
                "last_name"           => "Duty",
                "gender"              => "male",
                "phone"               => "+255712345683",
                "email"               => "duty@neovam.co.tz",
                "staff_type"          => "Teacher on Duty",
                "department"          => "Academic",
                "role_title"          => "Duty Teacher",
                "status"              => "active",
                "assigned_classrooms" => "7,8",
            ]
        );

        /* ============================================
           7. PARENT
           ============================================ */
        $parentUser = User::updateOrCreate(
            ["email" => "parent@neovam.co.tz"],
            [
                "name"      => "Mr. Parent",
                "password"  => $defaultPassword,
                "phone"     => "+255700000001",
                "school_id" => $school->id,
            ]
        );
        $parentUser->syncRoles(["parent"]);

        /* ============================================
           8. STUDENT
           ============================================ */
        $studentUser = User::updateOrCreate(
            ["email" => "student@neovam.co.tz"],
            [
                "name"      => "Alice Student",
                "password"  => $defaultPassword,
                "phone"     => "+255700000002",
                "school_id" => $school->id,
            ]
        );
        $studentUser->syncRoles(["student"]);

        /* ============================================
           9. DEMO STUDENTS (for parent view)
           ============================================ */
        $demoStudents = [
            [
                "admission_no" => "NEO-DEMO-001",
                "first_name"   => "Alice",
                "last_name"    => "Student",
                "gender"       => "female",
                "level"        => "primary",
                "parent_name"  => "Mr. Parent",
                "parent_phone" => "+255700000001",
                "user_id"      => $studentUser->id,
                "classroom_id" => 4,
            ],
            [
                "admission_no" => "NEO-DEMO-002",
                "first_name"   => "Bob",
                "last_name"    => "Student",
                "gender"       => "male",
                "level"        => "primary",
                "parent_name"  => "Mr. Parent",
                "parent_phone" => "+255700000001",
                "classroom_id" => 5,
            ],
        ];

        foreach ($demoStudents as $ds) {
            Student::updateOrCreate(
                ["admission_no" => $ds["admission_no"]],
                array_merge($ds, [
                    "school_id" => $school->id,
                    "status"    => "active",
                ])
            );
        }

        $this->command->info("All demo users created successfully.");
    }
}