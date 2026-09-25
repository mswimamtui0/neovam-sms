<?php

namespace App\Services\Academic;

use App\Models\ClassRoom;
use App\Models\Promotion;
use App\Models\Student;

class PromotionService
{
    /**
     * Ordered progression chain.
     * Key = current class name pattern, Value = next class name pattern.
     */
    public static function nextClass(ClassRoom $class): ?array
    {
        $name  = trim($class->name);
        $level = $class->level;

        // Pre-Primary chain
        $prePrimary = [
            "Nursery"   => ["KG", "kg"],
            "KG"        => ["Pre-Unit", "pre_unit"],
            "Pre-Unit"  => ["Standard 1", "primary"],
        ];

        // Primary chain
        $primary = [
            "Standard 1" => ["Standard 2", "primary"],
            "Standard 2" => ["Standard 3", "primary"],
            "Standard 3" => ["Standard 4", "primary"],
            "Standard 4" => ["Standard 5", "primary"],
            "Standard 5" => ["Standard 6", "primary"],
            "Standard 6" => ["Standard 7", "primary"],
            "Standard 7" => ["Form 1", "secondary"],     // ← level change
        ];

        // Secondary chain
        $secondary = [
            "Form 1" => ["Form 2", "secondary"],
            "Form 2" => ["Form 3", "secondary"],
            "Form 3" => ["Form 4", "secondary"],
            "Form 4" => ["Form 5", "alevel"],            // ← level change
        ];

        // A-Level chain
        $alevel = [
            "Form 5" => ["Form 6", "alevel"],
            "Form 6" => [null, null],                     // ← graduated
        ];

        $chain = array_merge($prePrimary, $primary, $secondary, $alevel);

        return $chain[$name] ?? null;
    }

    /**
     * Preview promotions for a whole school or a level.
     * Returns array of [student, from_class, to_class, status].
     */
    public static function preview(?string $level = null, bool $excludeRepeaters = true): array
    {
        $query = Student::with("classroom")->where("status", "active");

        if ($level) {
            $query->where("level", $level);
        }

        $students = $query->get();
        $rows = [];

        foreach ($students as $student) {
            $fromClass = $student->classroom;

            if (!$fromClass) {
                $rows[] = [
                    "student"    => $student,
                    "from_class" => null,
                    "to_class"   => null,
                    "status"     => "no_class",
                    "next_name"  => null,
                    "next_level" => null,
                ];
                continue;
            }

            $next = self::nextClass($fromClass);

            if ($next === null) {
                $rows[] = [
                    "student"    => $student,
                    "from_class" => $fromClass,
                    "to_class"   => null,
                    "status"     => "no_mapping",
                    "next_name"  => null,
                    "next_level" => null,
                ];
                continue;
            }

            [$nextName, $nextLevel] = $next;

            // Graduated (Form 6)
            if ($nextName === null) {
                $rows[] = [
                    "student"    => $student,
                    "from_class" => $fromClass,
                    "to_class"   => null,
                    "status"     => "graduated",
                    "next_name"  => null,
                    "next_level" => null,
                ];
                continue;
            }

            // Find next class in same school
            $toClass = ClassRoom::where("name", $nextName)
                ->where("level", $nextLevel)
                ->first();

            $rows[] = [
                "student"    => $student,
                "from_class" => $fromClass,
                "to_class"   => $toClass,
                "status"     => $toClass ? "will_promote" : "next_class_missing",
                "next_name"  => $nextName,
                "next_level" => $nextLevel,
            ];
        }

        return $rows;
    }

    /**
     * Execute promotions for the given student IDs.
     * Returns [promoted, graduated, skipped].
     */
    public static function promote(array $studentIds, string $academicYear): array
    {
        $promoted   = 0;
        $graduated  = 0;
        $skipped    = 0;

        foreach ($studentIds as $id) {
            $student = Student::with("classroom")->find($id);
            if (!$student || !$student->classroom) { $skipped++; continue; }

            $fromClass = $student->classroom;
            $next      = self::nextClass($fromClass);

            // Graduated
            if ($next !== null && $next[0] === null) {
                Promotion::create([
                    "student_id"        => $student->id,
                    "from_classroom_id" => $fromClass->id,
                    "to_classroom_id"   => null,
                    "from_level"        => $fromClass->level,
                    "to_level"          => null,
                    "academic_year"     => $academicYear,
                    "status"            => "graduated",
                    "promoted_by"       => auth()->id(),
                    "promoted_at"       => now(),
                ]);

                $student->update(["status" => "graduated"]);
                $graduated++;
                continue;
            }

            if ($next === null) { $skipped++; continue; }

            [$nextName, $nextLevel] = $next;
            $toClass = ClassRoom::where("name", $nextName)
                ->where("level", $nextLevel)
                ->first();

            if (!$toClass) { $skipped++; continue; }

            // Record promotion
            Promotion::create([
                "student_id"        => $student->id,
                "from_classroom_id" => $fromClass->id,
                "to_classroom_id"   => $toClass->id,
                "from_level"        => $fromClass->level,
                "to_level"          => $toClass->level,
                "academic_year"     => $academicYear,
                "status"            => "promoted",
                "promoted_by"       => auth()->id(),
                "promoted_at"       => now(),
            ]);

            // Move the student
            $student->update([
                "classroom_id" => $toClass->id,
                "level"        => $toClass->level,
            ]);

            $promoted++;
        }

        return compact("promoted","graduated","skipped");
    }
}