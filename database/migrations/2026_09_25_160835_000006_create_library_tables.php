<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("library_books", function (Blueprint $table) {
 $table->id();
 $table->string("title");
 $table->string("author")->nullable();
 $table->string("isbn")->nullable();
 $table->string("category")->nullable();
 $table->integer("total_copies")->default(1);
 $table->integer("available_copies")->default(1);
 $table->timestamps();
 });

 Schema::create("library_loans", function (Blueprint $table) {
 $table->id();
 $table->foreignId("book_id")->constrained("library_books")->cascadeOnDelete();
 $table->foreignId("student_id")->constrained()->cascadeOnDelete();
 $table->date("borrowed_at");
 $table->date("due_at");
 $table->date("returned_at")->nullable();
 $table->string("status")->default("borrowed"); // borrowed, returned, overdue
 $table->timestamps();
 });
 }
 public function down(): void {
 Schema::dropIfExists("library_loans");
 Schema::dropIfExists("library_books");
 }
};