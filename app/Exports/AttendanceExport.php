<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
 public function __construct(protected ?Carbon $from = null, protected ?Carbon $to = null) {}

 public function collection()
 {
 return Attendance::with(["student","classroom"])
 ->when($this->from, fn($q) => $q->where("date", ">=", $this->from))
 ->when($this->to, fn($q) => $q->where("date", "<=", $this->to))
 ->orderByDesc("date")
 ->get();
 }

 public function headings(): array
 {
 return ["Date","Student","Adm No","Class","Status","Recorded By","SMS Sent"];
 }

 public function map($a): array
 {
 return [
 $a->date?->format("Y-m-d"),
 $a->student?->full_name,
 $a->student?->admission_no,
 $a->classroom?->name,
 ucfirst($a->status),
 $a->recorded_by,
 $a->sms_sent ? "Yes" : "No",
 ];
 }

 public function styles(Worksheet $sheet)
 {
 return [1 => ["font" => ["bold" => true, "color" => ["rgb" => "FFFFFF"]]]];
 }
}