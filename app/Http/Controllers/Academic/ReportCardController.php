<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Student;
use App\Services\Academic\ReportCardService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportCardController extends Controller
{
    /**
     * List all exams to pick from.
     */
    public function index()
    {
        $exams = Exam::with("classroom")->latest()->paginate(20);
        return view("academic.report-cards.index", compact("exams"));
    }

    /**
     * Selector page — shows all students of the exam's class.
     */
    public function exam(Exam $exam)
    {
        $exam->load("classroom");

        $query = Student::with("classroom")->where("status", "active");
        if ($exam->classroom_id) {
            $query->where("classroom_id", $exam->classroom_id);
        }

        $students = $query->get()->map(function ($s) use ($exam) {
            $s->has_result = Result::where("exam_id", $exam->id)
                ->where("student_id", $s->id)->exists();
            return $s;
        });

        return view("academic.report-cards.exam", compact("exam","students"));
    }

    /**
     * Generate a single student report card as PDF (stream to browser).
     */
    public function single(Exam $exam, Student $student)
    {
        $data = ReportCardService::build($student, $exam);

        if (!$data) {
            return back()->with("error", "No results for this student in this exam.");
        }

        $data["comment"] = ReportCardService::comment($data["average"]);

        $pdf = Pdf::loadView("academic.report-cards.pdf", $data)
            ->setPaper("A4", "portrait");

        $filename = "report-card-" . $student->admission_no . "-" . $exam->id . ".pdf";
        return $pdf->stream($filename);
    }

    /**
     * Download a single report card.
     */
    public function download(Exam $exam, Student $student)
    {
        $data = ReportCardService::build($student, $exam);

        if (!$data) {
            return back()->with("error", "No results for this student.");
        }

        $data["comment"] = ReportCardService::comment($data["average"]);

        $pdf = Pdf::loadView("academic.report-cards.pdf", $data)
            ->setPaper("A4", "portrait");

        $filename = "report-card-" . $student->admission_no . "-" . $exam->id . ".pdf";
        return $pdf->download($filename);
    }

    /**
     * Bulk PDF — one report card per student, all merged into a single PDF.
     */
    public function bulk(Exam $exam)
    {
        $exam->load("classroom");

        $query = Student::with("classroom")->where("status", "active");
        if ($exam->classroom_id) {
            $query->where("classroom_id", $exam->classroom_id);
        }

        $students = $query->get();

        $html = "";
        foreach ($students as $student) {
            $data = ReportCardService::build($student, $exam);
            if (!$data) continue;
            $data["comment"] = ReportCardService::comment($data["average"]);
            $data["is_bulk"] = true;
            $html .= view("academic.report-cards.pdf", $data)->render();
            $html .= "<div style='page-break-after: always;'></div>";
        }

        if (!$html) {
            return back()->with("error", "No results to export.");
        }

        $pdf = Pdf::loadHTML($html)->setPaper("A4", "portrait");
        $filename = "report-cards-" . str_replace(" ", "-", $exam->name) . ".pdf";
        return $pdf->download($filename);
    }
}