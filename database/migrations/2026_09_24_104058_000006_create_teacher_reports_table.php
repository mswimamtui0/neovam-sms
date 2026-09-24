<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("teacher_reports", function (Blueprint $table) {
            $table->id();
            $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
            $table->string("report_type")->default("weekly");
            $table->date("week_start");
            $table->date("week_end");
            $table->text("summary");
            $table->text("challenges")->nullable();
            $table->text("next_plan")->nullable();
            $table->string("status")->default("submitted");
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("teacher_reports"); }
};