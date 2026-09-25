<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("staff_department_history", function (Blueprint $table) {
 $table->id();
 $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
 $table->string("from_roles")->nullable();
 $table->string("to_roles")->nullable();
 $table->string("from_department")->nullable();
 $table->string("to_department")->nullable();
 $table->date("effective_date");
 $table->text("reason")->nullable();
 $table->foreignId("changed_by")->nullable()->constrained("users")->nullOnDelete();
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("staff_department_history"); }
};