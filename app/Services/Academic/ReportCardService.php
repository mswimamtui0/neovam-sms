<?php

namespace App\Services\Academic;

use App\Models\Exam;
use App\Models\Result;
use App\Models\School;
use App\Models\Student;

class ReportCardService
{
 /**
 * Build report card data for one student + exam.
 */
 public static function build(Student $student, Exam $exam): ?array
 {
 $results = Result::where("exam_id", $exam->id)
 ->where("student_id", $student->id)
 ->get();

 if ($results->isEmpty()) {
 return null;
 }

 $total = $results->sum("marks");
 $average = round($results->avg("marks"), 2);
 $first = $results->first();

 // Grade from average
 $overallGrade = self::grade($average);

 // Subjects with grades
 $subjects = $results->map(fn($r) => [
 "subject" => $r->subject,
 "marks" => $r->marks,
 "grade" => $r->grade ?? self::grade($r->marks),
 ]);

 return [
 "student" => $student,
 "exam" => $exam,
 "school" => School::first(),
 "subjects" => $subjects,
 "total" => $total,
 "average" => $average,
 "grade" => $overallGrade,
 "position" => $first->position,
 "class_size" => $first->class_size,
 "classroom" => $student->classroom?->name,
 "term" => $exam->term,
 "year" => date("Y"),
 ];
 }

 public static function grade(float $marks): string
 {
 return match (true) {
 $marks >= 75 => "A",
 $marks >= 65 => "B",
 $marks >= 50 => "C",
 $marks >= 40 => "D",
 $marks >= 30 => "E",
 default => "F",
 };
 }

 /**
 * Return a comment based on average.
 */
 public static function comment(float $average): string
 {
 return match (true) {
 $average >= 75 => "Excellent performance. Keep up the outstanding work.",
 $average >= 65 => "Very good. Continue with the same effort.",
 $average >= 50 => "Good. Aim for higher marks next term.",
 $average >= 40 => "Fair. More effort is needed.",
 $average >= 30 => "Below expectation. Please improve.",
 default => "Poor. Serious improvement required.",
 };
 }
}