<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("duty_rosters", function (Blueprint $table) {
            $table->id();
            $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
            $table->date("duty_date");
            $table->string("duty_type")->default("general");
            $table->string("shift")->default("day");
            $table->string("location")->nullable();
            $table->text("notes")->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("duty_rosters"); }
};