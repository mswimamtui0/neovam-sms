<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
 public function __construct(protected ?Carbon $from = null, protected ?Carbon $to = null) {}

 public function collection()
 {
 return Payment::with(["student","invoice"])
 ->when($this->from, fn($q) => $q->where("payment_date", ">=", $this->from))
 ->when($this->to, fn($q) => $q->where("payment_date", "<=", $this->to))
 ->orderByDesc("payment_date")
 ->get();
 }

 public function headings(): array
 {
 return ["Receipt","Date","Student","Adm No","Term","Amount","Method","Reference","Recorded By"];
 }

 public function map($p): array
 {
 return [
 $p->receipt_no,
 $p->payment_date?->format("Y-m-d"),
 $p->student?->full_name,
 $p->student?->admission_no,
 $p->invoice?->term,
 $p->amount,
 ucfirst(str_replace("_"," ",$p->method)),
 $p->reference,
 $p->recorder?->name,
 ];
 }

 public function styles(Worksheet $sheet)
 {
 return [1 => ["font" => ["bold" => true, "color" => ["rgb" => "FFFFFF"]]]];
 }
}