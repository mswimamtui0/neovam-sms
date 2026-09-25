<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("health_records", function (Blueprint $table) {
 $table->id();
 $table->foreignId("student_id")->constrained()->cascadeOnDelete();
 $table->foreignId("staff_id")->nullable()->constrained("staff")->nullOnDelete();
 $table->string("record_type"); // illness, injury, first_aid, hygiene, referral, checkup
 $table->string("title");
 $table->text("description");
 $table->text("treatment")->nullable();
 $table->string("severity")->default("mild"); // mild, moderate, severe
 $table->boolean("referred_hospital")->default(false);
 $table->boolean("parent_notified")->default(false);
 $table->date("record_date");
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("health_records"); }
};