<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("security_logs", function (Blueprint $table) {
 $table->id();
 $table->foreignId("staff_id")->nullable()->constrained("staff")->nullOnDelete();
 $table->string("log_type"); // patrol, gate_duty, drill, visitor, incident
 $table->string("location")->nullable();
 $table->text("description");
 $table->string("status")->default("normal"); // normal, alert, resolved
 $table->date("log_date");
 $table->time("log_time")->nullable();
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("security_logs"); }
};