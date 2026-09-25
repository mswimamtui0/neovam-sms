<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("sms_trigger_settings", function (Blueprint $table) {
 $table->id();
 $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
 $table->string("trigger_key")->unique();
 $table->string("label");
 $table->string("category")->default("general");
 $table->text("description")->nullable();
 $table->boolean("is_enabled")->default(true);
 $table->boolean("is_critical")->default(false); // critical = cannot disable
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("sms_trigger_settings"); }
};