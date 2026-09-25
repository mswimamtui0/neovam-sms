<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("promotions", function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->foreignId("from_classroom_id")->nullable()->constrained("classrooms")->nullOnDelete();
            $table->foreignId("to_classroom_id")->nullable()->constrained("classrooms")->nullOnDelete();
            $table->string("from_level")->nullable();
            $table->string("to_level")->nullable();
            $table->string("academic_year");
            $table->string("term")->nullable();
            $table->string("status")->default("promoted"); // promoted, repeated, graduated, transferred
            $table->text("notes")->nullable();
            $table->foreignId("promoted_by")->nullable()->constrained("users")->nullOnDelete();
            $table->timestamp("promoted_at")->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("promotions"); }
};