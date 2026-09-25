<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
 use HasFactory;

 protected $fillable = [
 "invoice_id","student_id","recorded_by","receipt_no",
 "amount","method","reference","payment_date","notes","sms_sent",
 ];

 protected $casts = ["payment_date" => "date","sms_sent" => "boolean"];

 public function invoice() { return $this->belongsTo(Invoice::class); }
 public function student() { return $this->belongsTo(Student::class); }
 public function recorder() { return $this->belongsTo(User::class, "recorded_by"); }
}