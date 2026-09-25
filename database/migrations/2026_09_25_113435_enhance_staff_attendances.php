<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 // If table does not exist, create it
 if (!Schema::hasTable("staff_attendances")) {
 Schema::create("staff_attendances", function (Blueprint $table) {
 $table->id();
 $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
 $table->date("attendance_date");
 $table->time("check_in")->nullable();
 $table->time("check_out")->nullable();
 $table->string("status")->default("present");
 $table->integer("minutes_late")->default(0);
 $table->integer("hours_worked")->default(0);
 $table->string("ip_address")->nullable();
 $table->string("device")->nullable();
 $table->text("notes")->nullable();
 $table->timestamps();

 $table->unique(["staff_id","attendance_date"], "unique_staff_day");
 $table->index("attendance_date");
 });
 return;
 }

 // Otherwise add new columns
 Schema::table("staff_attendances", function (Blueprint $table) {
 if (!Schema::hasColumn("staff_attendances","minutes_late")) {
 $table->integer("minutes_late")->default(0)->after("status");
 }
 if (!Schema::hasColumn("staff_attendances","hours_worked")) {
 $table->integer("hours_worked")->default(0)->after("minutes_late");
 }
 if (!Schema::hasColumn("staff_attendances","ip_address")) {
 $table->string("ip_address")->nullable()->after("hours_worked");
 }
 if (!Schema::hasColumn("staff_attendances","device")) {
 $table->string("device")->nullable()->after("ip_address");
 }
 });
 }

 public function down(): void {
 Schema::table("staff_attendances", function (Blueprint $table) {
 $table->dropColumn(["minutes_late","hours_worked","ip_address","device"]);
 });
 }
};