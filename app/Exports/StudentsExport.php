<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
 public function __construct(protected ?string $level = null) {}

 public function collection()
 {
 return Student::withoutGlobalScopes()
 ->with("classroom")
 ->when($this->level, fn($q) => $q->where("level", $this->level))
 ->orderBy("level")->orderBy("first_name")
 ->get();
 }

 public function headings(): array
 {
 return [
 "Adm No","First Name","Last Name","Gender","DOB","Level","Class","Stream",
 "Parent Name","Parent Phone","Parent Email","Status",
 ];
 }

 public function map($s): array
 {
 return [
 $s->admission_no,
 $s->first_name,
 $s->last_name,
 ucfirst($s->gender),
 $s->dob?->format("Y-m-d"),
 ucfirst($s->level),
 $s->classroom?->name,
 $s->classroom?->stream,
 $s->parent_name,
 $s->parent_phone,
 $s->parent_email,
 ucfirst($s->status),
 ];
 }

 public function styles(Worksheet $sheet)
 {
 return [
 1 => ["font" => ["bold" => true, "color" => ["rgb" => "FFFFFF"]]],
 ];
 }
}