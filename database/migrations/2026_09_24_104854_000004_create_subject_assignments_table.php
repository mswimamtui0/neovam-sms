<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("subject_assignments", function (Blueprint $table) {
            $table->id();
            $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
            $table->foreignId("classroom_id")->constrained()->cascadeOnDelete();
            $table->foreignId("subject_id")->constrained()->cascadeOnDelete();
            $table->integer("periods_per_week")->default(1);
            $table->timestamps();

            $table->unique(["staff_id","classroom_id","subject_id"], "unique_assignment");
        });
    }
    public function down(): void { Schema::dropIfExists("subject_assignments"); }
};