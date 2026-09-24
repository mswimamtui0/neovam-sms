<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("class_meeting_notes", function (Blueprint $table) {
            $table->id();
            $table->foreignId("classroom_id")->constrained()->cascadeOnDelete();
            $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
            $table->date("meeting_date");
            $table->string("topic");
            $table->text("notes");
            $table->text("decisions")->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("class_meeting_notes"); }
};