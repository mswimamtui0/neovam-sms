<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("backups", function (Blueprint $table) {
 $table->id();
 $table->foreignId("school_id")->nullable()->constrained()->cascadeOnDelete();
 $table->string("filename");
 $table->string("disk")->default("local");
 $table->string("path");
 $table->bigInteger("size")->default(0); // bytes
 $table->string("type")->default("manual"); // manual | auto | pre_restore
 $table->string("scope")->default("full"); // full | school | tables
 $table->string("status")->default("pending"); // pending | completed | failed
 $table->text("notes")->nullable();
 $table->json("meta")->nullable(); // extra info (tables, rows count)
 $table->foreignId("created_by")->nullable()->constrained("users")->nullOnDelete();
 $table->timestamps();
 });
 }
 public function down(): void { Schema::dropIfExists("backups"); }
};