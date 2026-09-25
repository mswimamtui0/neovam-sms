<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeReminder extends Model {
    use HasFactory;

    protected $fillable = [
        "invoice_id","student_id","reminder_type","due_date",
        "balance","sms_sent","sms_sent_at","sms_status","notes",
    ];

    protected $casts = [
        "due_date"    => "date",
        "sms_sent"    => "boolean",
        "sms_sent_at" => "datetime",
        "balance"     => "decimal:2",
    ];

    public function invoice() { return $this->belongsTo(Invoice::class); }
    public function student() { return $this->belongsTo(Student::class); }

    public function typeLabel(): string
    {
        return match ($this->reminder_type) {
            "before_due"    => "3 Days Before Due",
            "on_due"        => "On Due Date",
            "after_due"     => "Overdue Follow-up",
            "overdue_final" => "Final Notice",
            default         => ucfirst($this->reminder_type),
        };
    }

    public function typeColor(): string
    {
        return match ($this->reminder_type) {
            "before_due"    => "blue",
            "on_due"        => "yellow",
            "after_due"     => "orange",
            "overdue_final" => "red",
            default         => "gray",
        };
    }
}