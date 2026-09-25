<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model {
 use HasFactory;

 protected $fillable = [
 "recipient","message","trigger","units","char_count",
 "status","delivery_status","gateway_ref",
 "sent_at","delivered_at","failed_at",
 "error_code","error_message","cost","network","delivery_attempts",
 ];

 protected $casts = [
 "units" => "integer",
 "char_count" => "integer",
 "delivery_attempts" => "integer",
 "cost" => "decimal:4",
 "sent_at" => "datetime",
 "delivered_at" => "datetime",
 "failed_at" => "datetime",
 ];

 /* ============ Scopes ============ */
 public function scopeSent($q) { return $q->where("status", "sent"); }
 public function scopeFailed($q) { return $q->where("status", "failed"); }
 public function scopePending($q) { return $q->where("status", "pending"); }
 public function scopeDelivered($q) { return $q->where("delivery_status", "delivered"); }
 public function scopeUndelivered($q) { return $q->where("delivery_status", "undelivered"); }

 /* ============ Markers ============ */
 public function markSent(?string $ref = null): void
 {
 $this->update([
 "status" => "sent",
 "delivery_status" => "sent",
 "gateway_ref" => $ref,
 "sent_at" => now(),
 "delivery_attempts" => ($this->delivery_attempts ?? 0) + 1,
 ]);
 }

 public function markDelivered(): void
 {
 $this->update([
 "delivery_status" => "delivered",
 "delivered_at" => now(),
 ]);
 }

 public function markUndelivered(string $reason = null, string $code = null): void
 {
 $this->update([
 "delivery_status" => "undelivered",
 "error_code" => $code,
 "error_message" => $reason,
 "failed_at" => now(),
 ]);
 }

 public function markFailed(string $reason = null, string $code = null): void
 {
 $this->update([
 "status" => "failed",
 "delivery_status" => "rejected",
 "error_code" => $code,
 "error_message" => $reason,
 "failed_at" => now(),
 ]);
 }

 /* ============ Helpers ============ */
 public function statusColor(): string
 {
 return match ($this->delivery_status) {
 "delivered" => "green",
 "undelivered" => "yellow",
 "rejected" => "red",
 "expired" => "red",
 "sent" => "blue",
 default => "gray",
 };
 }
}