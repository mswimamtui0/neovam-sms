<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("welfare_notes", function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
            $table->string("type"); // health, hygiene, uniform, food, other
            $table->text("observation");
            $table->text("action")->nullable();
            $table->date("note_date");
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("welfare_notes"); }
};