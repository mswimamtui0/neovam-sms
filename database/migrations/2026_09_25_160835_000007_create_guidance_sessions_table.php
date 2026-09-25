<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("guidance_sessions", function (Blueprint $table) {
 $table->id();
 $table->foreignId("student_id")->constrained()->cascadeOnDelete();
 $table->foreignId("staff_id")->nullable()->constrained("staff")->nullOnDelete();
 $table->string("session_type"); // personal, academic, career, family
 $table->string("topic");
 $table->text("notes");
 $table->text("action_plan")->nullable();
 $table->boolean("follow_up_needed")->default(false);
 $table->date("session_date");
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("guidance_sessions"); }
};