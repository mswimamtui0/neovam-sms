<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("sms_control_settings", function (Blueprint $table) {
 $table->id();
 $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
 $table->string("key")->unique(); // master_switch, schedule_enabled, etc.
 $table->string("label");
 $table->text("value")->nullable();
 $table->string("type")->default("boolean"); // boolean, integer, string, json
 $table->text("description")->nullable();
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("sms_control_settings"); }
};