<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model {
 use HasFactory;

 protected $fillable = [
 "staff_id",
 "basic_salary","allowance_house","allowance_transport","allowance_meal","allowance_other",
 "deduction_tax","deduction_nssf","deduction_loan","deduction_other",
 "bank_name","bank_account","payment_method",
 "effective_from","is_active","notes",
 ];

 protected $casts = [
 "basic_salary" => "decimal:2",
 "allowance_house" => "decimal:2",
 "allowance_transport" => "decimal:2",
 "allowance_meal" => "decimal:2",
 "allowance_other" => "decimal:2",
 "deduction_tax" => "decimal:2",
 "deduction_nssf" => "decimal:2",
 "deduction_loan" => "decimal:2",
 "deduction_other" => "decimal:2",
 "effective_from" => "date",
 "is_active" => "boolean",
 ];

 public function staff() { return $this->belongsTo(Staff::class); }

 public function totalAllowances(): float
 {
 return (float) ($this->allowance_house + $this->allowance_transport + $this->allowance_meal + $this->allowance_other);
 }

 public function totalDeductions(): float
 {
 return (float) ($this->deduction_tax + $this->deduction_nssf + $this->deduction_loan + $this->deduction_other);
 }

 public function grossPay(): float
 {
 return (float) ($this->basic_salary + $this->totalAllowances());
 }

 public function netPay(): float
 {
 return (float) ($this->grossPay() - $this->totalDeductions());
 }

 /**
 * Get the current active salary structure for a staff member.
 */
 public static function currentFor(int $staffId): ?self
 {
 return self::where("staff_id", $staffId)
 ->where("is_active", true)
 ->latest("effective_from")
 ->first();
 }
}