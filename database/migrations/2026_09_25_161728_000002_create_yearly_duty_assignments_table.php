<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("yearly_duty_assignments", function (Blueprint $table) {
 $table->id();
 $table->foreignId("calendar_id")->constrained("yearly_duty_calendars")->cascadeOnDelete();
 $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
 $table->date("duty_date");
 $table->string("duty_type")->default("general");
 $table->string("shift")->default("day"); // day | evening | night
 $table->string("location")->nullable();
 $table->text("notes")->nullable();
 $table->boolean("sms_sent")->default(false);
 $table->timestamps();

 $table->index(["duty_date","shift"]);
 });
 }
 public function down(): void { Schema::dropIfExists("yearly_duty_assignments"); }
};