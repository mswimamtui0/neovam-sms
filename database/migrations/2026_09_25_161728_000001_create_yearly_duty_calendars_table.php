<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("yearly_duty_calendars", function (Blueprint $table) {
 $table->id();
 $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
 $table->integer("year");
 $table->string("title");
 $table->text("notes")->nullable();
 $table->string("status")->default("draft"); // draft | active | archived
 $table->foreignId("created_by")->nullable()->constrained("users")->nullOnDelete();
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("yearly_duty_calendars"); }
};