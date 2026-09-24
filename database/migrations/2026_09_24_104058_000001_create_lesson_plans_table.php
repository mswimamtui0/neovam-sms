<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("lesson_plans", function (Blueprint $table) {
            $table->id();
            $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
            $table->foreignId("classroom_id")->nullable()->constrained()->nullOnDelete();
            $table->foreignId("subject_id")->nullable()->constrained()->nullOnDelete();
            $table->date("date");
            $table->string("topic");
            $table->text("objectives")->nullable();
            $table->text("activities")->nullable();
            $table->text("materials")->nullable();
            $table->string("status")->default("draft");
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("lesson_plans"); }
};