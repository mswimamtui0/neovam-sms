<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("sms_skipped_messages", function (Blueprint $table) {
 $table->id();
 $table->string("recipient");
 $table->text("message");
 $table->string("trigger");
 $table->string("skip_reason"); // master_off, category_off, trigger_off, schedule, rate_limit
 $table->timestamp("would_have_sent_at");
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("sms_skipped_messages"); }
};