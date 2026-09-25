<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("handover_notes", function (Blueprint $table) {
 $table->id();
 $table->foreignId("from_staff_id")->constrained("staff")->cascadeOnDelete();
 $table->foreignId("to_staff_id")->nullable()->constrained("staff")->nullOnDelete();
 $table->date("handover_date");
 $table->text("notes");
 $table->boolean("acknowledged")->default(false);
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("handover_notes"); }
};