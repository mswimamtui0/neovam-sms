<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {
    use HasFactory, BelongsToSchool;

    protected $fillable = [
        "school_id","student_id","invoice_no","term","year",
        "amount","amount_paid","balance","due_date","status",
    ];

    protected $casts = ["due_date" => "date"];

    public function student()  { return $this->belongsTo(Student::class); }
    public function payments() { return $this->hasMany(Payment::class); }

    public function refreshBalance(): void {
        $paid = $this->payments()->sum("amount");
        $balance = $this->amount - $paid;

        $status = "pending";
        if ($balance <= 0)        $status = "paid";
        elseif ($paid > 0)        $status = "partial";
        if ($balance > 0 && $this->due_date && $this->due_date->isPast()) $status = "overdue";

        $this->update([
            "amount_paid" => $paid,
            "balance"     => $balance,
            "status"      => $status,
        ]);
    }
}