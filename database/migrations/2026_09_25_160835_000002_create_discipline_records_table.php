<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("discipline_records", function (Blueprint $table) {
 $table->id();
 $table->foreignId("student_id")->constrained()->cascadeOnDelete();
 $table->foreignId("staff_id")->nullable()->constrained("staff")->nullOnDelete();
 $table->string("offense");
 $table->text("description");
 $table->string("action_taken"); // warning, detention, suspension, parent_meeting, counseling, praise
 $table->text("notes")->nullable();
 $table->boolean("parent_notified")->default(false);
 $table->date("incident_date");
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("discipline_records"); }
};