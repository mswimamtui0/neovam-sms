<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
 use HasFactory;

 protected $fillable = [
 "name","group_name","code","branch_code","subdomain",
 "phone","email","address","logo",
 "has_primary","has_secondary","has_alevel",
 "is_active","parent_school_id","subscription_plan","subscription_expires_at",
 "sms_balance_units","settings",
 ];

 protected $casts = [
 "has_primary" => "boolean",
 "has_secondary" => "boolean",
 "has_alevel" => "boolean",
 "is_active" => "boolean",
 "subscription_expires_at" => "date",
 "sms_balance_units" => "integer",
 ];

 /* ============ Relationships ============ */
 public function users() { return $this->hasMany(User::class); }
 public function classrooms() { return $this->hasMany(ClassRoom::class); }
 public function students() { return $this->hasMany(Student::class); }
 public function staff() { return $this->hasMany(Staff::class); }
 public function exams() { return $this->hasMany(Exam::class); }

 public function parent()
 {
 return $this->belongsTo(School::class, "parent_school_id");
 }

 public function branches()
 {
 return $this->hasMany(School::class, "parent_school_id");
 }

 /* ============ Level Helpers ============ */
 public function enabledLevels(): array
 {
 return collect([
 "nursery" => true,
 "kg" => true,
 "pre_unit" => true,
 "primary" => $this->has_primary,
 "secondary" => $this->has_secondary,
 "alevel" => $this->has_alevel,
 ])->filter()->keys()->toArray();
 }

 public function isLevelEnabled(string $level): bool
 {
 return in_array($level, $this->enabledLevels(), true);
 }

 /* ============ Subscription Helpers ============ */
 public function isSubscriptionActive(): bool
 {
 if (!$this->subscription_expires_at) return true;
 return $this->subscription_expires_at->isFuture();
 }

 public function isMainBranch(): bool
 {
 return $this->parent_school_id === null;
 }

 public function isBranch(): bool
 {
 return $this->parent_school_id !== null;
 }

 /* ============ Settings ============ */
 public function setting(string $key, $default = null)
 {
 $settings = $this->settings ? json_decode($this->settings, true) : [];
 return $settings[$key] ?? $default;
 }

 public function setSetting(string $key, $value): void
 {
 $settings = $this->settings ? json_decode($this->settings, true) : [];
 $settings[$key] = $value;
 $this->update(["settings" => json_encode($settings)]);
 }
}