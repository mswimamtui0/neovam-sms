<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("class_discipline_logs", function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
            $table->string("type");     // warning, detention, suspension, praise
            $table->text("reason");
            $table->text("action_taken")->nullable();
            $table->date("log_date");
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("class_discipline_logs"); }
};