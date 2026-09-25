<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("academic_calendars", function (Blueprint $table) {
 $table->id();
 $table->string("term");
 $table->integer("year");
 $table->date("term_start");
 $table->date("term_end");
 $table->date("exam_start")->nullable();
 $table->date("exam_end")->nullable();
 $table->text("notes")->nullable();
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("academic_calendars"); }
};