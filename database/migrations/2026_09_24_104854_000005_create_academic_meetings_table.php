<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("academic_meetings", function (Blueprint $table) {
 $table->id();
 $table->string("title");
 $table->string("meeting_type")->default("subject");
 $table->date("meeting_date");
 $table->time("meeting_time")->nullable();
 $table->string("venue")->nullable();
 $table->text("agenda")->nullable();
 $table->text("minutes")->nullable();
 $table->string("status")->default("planned");
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("academic_meetings"); }
};