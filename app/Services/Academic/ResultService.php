<?php

namespace App\Services\Academic;

use App\Models\Exam;
use App\Models\Result;
use App\Models\Student;

class ResultService
{
    /**
     * Compute grades from marks.
     */
    public static function grade(int $marks): string
    {
        return match (true) {
            $marks >= 75 => "A",
            $marks >= 65 => "B",
            $marks >= 50 => "C",
            $marks >= 40 => "D",
            $marks >= 30 => "E",
            default      => "F",
        };
    }

    /**
     * Process an exam's results: compute totals, averages, grades, positions.
     * Called before publishing.
     */
    public static function process(Exam $exam): array
    {
        $results = Result::where("exam_id", $exam->id)->get();

        if ($results->isEmpty()) {
            return ["success" => false, "message" => "No results found for this exam."];
        }

        // Group by student
        $byStudent = $results->groupBy("student_id");

        // Compute averages per student
        $studentAverages = [];
        foreach ($byStudent as $studentId => $studentResults) {
            $totalMarks = $studentResults->sum("marks");
            $average    = round($studentResults->avg("marks"), 2);

            $studentAverages[$studentId] = [
                "total"   => $totalMarks,
                "average" => $average,
            ];

            // Update each result row
            foreach ($studentResults as $r) {
                $r->update([
                    "grade"       => self::grade($r->marks),
                    "total_marks" => $totalMarks,
                    "average"     => $average,
                    "class_size"  => $byStudent->count(),
                ]);
            }
        }

        // Rank students by average
        arsort($studentAverages);

        $position = 1;
        foreach ($studentAverages as $studentId => $data) {
            Result::where("exam_id", $exam->id)
                ->where("student_id", $studentId)
                ->update(["position" => $position]);
            $position++;
        }

        return [
            "success" => true,
            "count"   => $byStudent->count(),
            "message" => "Processed " . $byStudent->count() . " students.",
        ];
    }

    /**
     * Build a short SMS summary for a student's results.
     */
    public static function smsSummary(int $studentId, int $examId): string
    {
        $first = Result::where("exam_id", $examId)
            ->where("student_id", $studentId)
            ->first();

        if (!$first) return "";

        return "Avg: {$first->average}% | Position: {$first->position}/{$first->class_size}";
    }
}