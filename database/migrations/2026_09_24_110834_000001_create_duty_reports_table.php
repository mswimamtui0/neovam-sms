<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("duty_reports", function (Blueprint $table) {
 $table->id();
 $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
 $table->string("report_type")->default("daily"); // daily, weekly
 $table->date("report_date");
 $table->text("morning_notes")->nullable();
 $table->text("break_notes")->nullable();
 $table->text("lunch_notes")->nullable();
 $table->text("evening_notes")->nullable();
 $table->text("night_notes")->nullable();
 $table->integer("incidents_count")->default(0);
 $table->text("summary");
 $table->text("handover")->nullable();
 $table->string("status")->default("submitted");
 $table->timestamps();

 $table->index(["staff_id","report_date"]);
 });
 }
 public function down(): void { Schema::dropIfExists("duty_reports"); }
};