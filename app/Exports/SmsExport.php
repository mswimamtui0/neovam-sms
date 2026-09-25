<?php

namespace App\Exports;

use App\Models\SmsLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class SmsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
 public function __construct(protected ?Carbon $from = null, protected ?Carbon $to = null) {}

 public function collection()
 {
 return SmsLog::when($this->from, fn($q) => $q->where("created_at", ">=", $this->from))
 ->when($this->to, fn($q) => $q->where("created_at", "<=", $this->to))
 ->orderByDesc("created_at")
 ->get();
 }

 public function headings(): array
 {
 return ["Date","Recipient","Trigger","Message","Units","Chars","Status","Delivery","Cost","Network"];
 }

 public function map($s): array
 {
 return [
 $s->created_at->format("Y-m-d H:i"),
 $s->recipient,
 $s->trigger,
 $s->message,
 $s->units,
 $s->char_count,
 ucfirst($s->status),
 ucfirst($s->delivery_status),
 $s->cost,
 $s->network,
 ];
 }

 public function styles(Worksheet $sheet)
 {
 return [1 => ["font" => ["bold" => true, "color" => ["rgb" => "FFFFFF"]]]];
 }
}