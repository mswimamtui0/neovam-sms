<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("boarding_logs", function (Blueprint $table) {
 $table->id();
 $table->foreignId("staff_id")->nullable()->constrained("staff")->nullOnDelete();
 $table->string("log_type"); // wake_up, roll_call, night_check, cleanliness, prep, incident
 $table->string("dormitory")->nullable();
 $table->text("description");
 $table->date("log_date");
 $table->time("log_time")->nullable();
 $table->integer("students_present")->default(0);
 $table->integer("students_absent")->default(0);
 $table->text("notes")->nullable();
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("boarding_logs"); }
};