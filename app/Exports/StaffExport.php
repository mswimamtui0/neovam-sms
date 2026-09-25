<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StaffExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
 public function collection()
 {
 return Staff::where("status", "active")
 ->orderBy("department")->orderBy("first_name")
 ->get();
 }

 public function headings(): array
 {
 return [
 "Staff No","First Name","Last Name","Gender","Phone","Email",
 "Staff Type","Department","Role Title","Employment Type","Status",
 ];
 }

 public function map($s): array
 {
 return [
 $s->staff_no,
 $s->first_name,
 $s->last_name,
 ucfirst($s->gender),
 $s->phone,
 $s->email,
 $s->staff_type,
 $s->department,
 $s->role_title,
 $s->employment_type,
 ucfirst($s->status),
 ];
 }

 public function styles(Worksheet $sheet)
 {
 return [1 => ["font" => ["bold" => true, "color" => ["rgb" => "FFFFFF"]]]];
 }
}