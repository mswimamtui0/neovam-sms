<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable("emergencies")) {
            return;
        }

        Schema::create("emergencies", function (Blueprint $table) {
            $table->id();
            $table->foreignId("school_id")->nullable()->index();
            $table->foreignId("department_id")->nullable()->index();
            $table->foreignId("staff_id")->nullable()->index();
            $table->string("recorded_by")->nullable();

            $table->foreignId("student_id")->nullable()->index();
            $table->string("student_name");
            $table->string("class_name")->nullable();

            $table->string("type")->default("illness");    // illness | injury | emotional | other
            $table->string("severity")->default("normal"); // critical | urgent | normal | info
            $table->text("description")->nullable();
            $table->text("action_taken")->nullable();
            $table->string("location")->nullable();

            // Notifications
            $table->boolean("head_notified")->default(false);
            $table->boolean("parent_notified")->default(false);
            $table->boolean("health_teacher_notified")->default(false);
            $table->timestamp("head_notified_at")->nullable();
            $table->timestamp("parent_notified_at")->nullable();
            $table->timestamp("health_teacher_notified_at")->nullable();

            // Follow-up
            $table->text("parent_response")->nullable();
            $table->text("follow_up")->nullable();
            $table->timestamp("resolved_at")->nullable();
            $table->string("status")->default("open");     // open | monitoring | resolved | closed

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("emergencies");
    }
};