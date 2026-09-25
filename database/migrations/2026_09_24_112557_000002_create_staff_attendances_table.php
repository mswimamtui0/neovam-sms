<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("staff_attendances", function (Blueprint $table) {
 $table->id();
 $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
 $table->date("attendance_date");
 $table->time("check_in")->nullable();
 $table->time("check_out")->nullable();
 $table->string("status")->default("present"); // present, absent, late, leave
 $table->text("notes")->nullable();
 $table->timestamps();

 $table->unique(["staff_id","attendance_date"]);
 });
 }
 public function down(): void { Schema::dropIfExists("staff_attendances"); }
};