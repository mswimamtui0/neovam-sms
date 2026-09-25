<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model {
    use HasFactory;

    protected $fillable = [
        "payroll_run_id","staff_id",
        "basic_salary","allowances","deductions",
        "gross_pay","net_pay","amount_paid",
        "status","paid_at","payment_reference","notes",
    ];

    protected $casts = [
        "basic_salary" => "decimal:2",
        "allowances"   => "decimal:2",
        "deductions"   => "decimal:2",
        "gross_pay"    => "decimal:2",
        "net_pay"      => "decimal:2",
        "amount_paid"  => "decimal:2",
        "paid_at"      => "datetime",
    ];

    public function payrollRun() { return $this->belongsTo(PayrollRun::class); }
    public function staff()      { return $this->belongsTo(Staff::class); }
}