<?php

namespace App\Exports;

use App\Models\Result;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResultsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
 public function __construct(protected ?int $examId = null) {}

 public function collection()
 {
 return Result::with(["student","exam"])
 ->when($this->examId, fn($q) => $q->where("exam_id", $this->examId))
 ->orderBy("exam_id")->orderBy("student_id")
 ->get();
 }

 public function headings(): array
 {
 return ["Exam","Term","Student","Adm No","Subject","Marks","Grade","Average","Position"];
 }

 public function map($r): array
 {
 return [
 $r->exam?->name,
 $r->exam?->term,
 $r->student?->full_name,
 $r->student?->admission_no,
 $r->subject,
 $r->marks,
 $r->grade,
 $r->average,
 $r->position,
 ];
 }

 public function styles(Worksheet $sheet)
 {
 return [1 => ["font" => ["bold" => true, "color" => ["rgb" => "FFFFFF"]]]];
 }
}