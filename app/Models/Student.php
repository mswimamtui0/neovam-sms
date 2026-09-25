<?php

namespace App\Models;

use App\Models\Scopes\ParentStudentScope;
use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Student extends Model
{
 use HasFactory, BelongsToSchool, LogsActivity;

 protected $fillable = [
 "school_id", "classroom_id", "user_id",
 "can_login", "login_enabled_at", "login_enabled_by",
 "admission_no", "first_name", "last_name", "gender", "dob", "level",
 "parent_name", "parent_phone", "parent_email",
 "status",
 ];

 protected $casts = [
 "dob" => "date",
 "can_login" => "boolean",
 "login_enabled_at" => "datetime",
 ];

 protected $appends = ["full_name"];

 protected static function booted(): void
 {
 static::addGlobalScope(new ParentStudentScope());
 }

 public function getActivitylogOptions(): LogOptions
 {
 return LogOptions::defaults()
 ->logOnly(["first_name","last_name","level","parent_phone","can_login"])
 ->logOnlyDirty()
 ->useLogName("student");
 }

 public function school() { return $this->belongsTo(School::class); }
 public function classroom() { return $this->belongsTo(ClassRoom::class, "classroom_id"); }
 public function user() { return $this->belongsTo(User::class); }
 public function attendances() { return $this->hasMany(Attendance::class); }
 public function results() { return $this->hasMany(Result::class); }
 public function incidents() { return $this->hasMany(Incident::class); }

 public function getFullNameAttribute(): string
 {
 return "{$this->first_name} {$this->last_name}";
 }

 /**
 * Enable student login: creates a user account + assigns student role.
 */
 public function enableLogin(?string $email = null, ?string $password = null): ?User
 {
 if ($this->user_id) return $this->user; // already has a user

 $email = $email ?: strtolower($this->admission_no) . "@neovam.co.tz";
 $password = $password ?: "Student@123";

 // Check if user already exists with this email
 $user = User::where("email", $email)->first();
 if (!$user) {
 $user = User::create([
 "name" => $this->full_name,
 "email" => $email,
 "password" => bcrypt($password),
 "phone" => null,
 "school_id" => $this->school_id,
 ]);
 $user->assignRole("student");
 }

 $this->update([
 "user_id" => $user->id,
 "can_login" => true,
 "login_enabled_at" => now(),
 "login_enabled_by" => auth()->user()?->name,
 ]);

 return $user;
 }

 /**
 * Disable student login: detaches the user account.
 */
 public function disableLogin(): void
 {
 if ($this->user_id) {
 $user = $this->user;
 $user?->syncRoles([]); // remove roles
 $user?->delete(); // delete user
 }

 $this->update([
 "user_id" => null,
 "can_login" => false,
 "login_enabled_at" => null,
 "login_enabled_by" => null,
 ]);
 }
}