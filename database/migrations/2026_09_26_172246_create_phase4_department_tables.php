<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $school = function (Blueprint $t) {
            $t->foreignId("school_id")->nullable()->index();
        };
        $audit = function (Blueprint $t) {
            $t->foreignId("department_id")->nullable()->index();
            $t->foreignId("staff_id")->nullable()->index();
            $t->string("recorded_by")->nullable();
            $t->timestamps();
        };

        // ---------- MUSIC ----------
        if (!Schema::hasTable("music_records")) {
            Schema::create("music_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("rehearsal");
                $t->string("title")->nullable();
                $t->string("venue")->nullable();
                $t->date("event_date")->nullable();
                $t->string("event_time")->nullable();
                $t->integer("participant_count")->default(0);
                $t->text("instruments_used")->nullable();
                $t->text("notes")->nullable();
                $t->string("status")->default("scheduled");
                $audit($t);
            });
        }

        // ---------- DRAMA / ARTS ----------
        if (!Schema::hasTable("drama_records")) {
            Schema::create("drama_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("rehearsal");
                $t->string("title")->nullable();
                $t->string("venue")->nullable();
                $t->date("event_date")->nullable();
                $t->string("event_time")->nullable();
                $t->integer("participant_count")->default(0);
                $t->text("props")->nullable();
                $t->text("notes")->nullable();
                $t->string("status")->default("scheduled");
                $audit($t);
            });
        }

        // ---------- AGRICULTURE / FARM ----------
        if (!Schema::hasTable("agriculture_records")) {
            Schema::create("agriculture_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("crop");
                $t->string("plot")->nullable();
                $t->string("crop_animal")->nullable();
                $t->string("activity")->nullable();
                $t->decimal("quantity", 14, 2)->default(0);
                $t->string("unit")->nullable();
                $t->text("notes")->nullable();
                $t->date("activity_date")->nullable();
                $t->string("status")->default("planned");
                $audit($t);
            });
        }

        // ---------- CHAPLAINCY ----------
        if (!Schema::hasTable("chaplaincy_records")) {
            Schema::create("chaplaincy_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("service");
                $t->string("title")->nullable();
                $t->string("venue")->nullable();
                $t->date("event_date")->nullable();
                $t->string("event_time")->nullable();
                $t->integer("attendees")->default(0);
                $t->text("notes")->nullable();
                $t->string("status")->default("scheduled");
                $audit($t);
            });
        }

        // ---------- ALUMNI ----------
        if (!Schema::hasTable("alumni_records")) {
            Schema::create("alumni_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("event");
                $t->string("alumni_name")->nullable();
                $t->string("graduation_year")->nullable();
                $t->string("contact_phone")->nullable();
                $t->string("contact_email")->nullable();
                $t->string("title")->nullable();
                $t->text("description")->nullable();
                $t->decimal("donation_amount", 14, 2)->default(0);
                $t->string("currency")->default("TZS");
                $t->date("event_date")->nullable();
                $t->string("status")->default("open");
                $audit($t);
            });
        }

        // ---------- SPECIAL NEEDS ----------
        if (!Schema::hasTable("special_needs_records")) {
            Schema::create("special_needs_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("student_name")->nullable();
                $t->string("class_name")->nullable();
                $t->string("need_type")->nullable();
                $t->text("description")->nullable();
                $t->text("support_plan")->nullable();
                $t->text("progress_notes")->nullable();
                $t->string("status")->default("active");
                $audit($t);
            });
        }

        // ---------- COUNSELLING / PSYCHOSOCIAL ----------
        if (!Schema::hasTable("psychosocial_records")) {
            Schema::create("psychosocial_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("student_name")->nullable();
                $t->string("class_name")->nullable();
                $t->string("session_type")->default("personal");
                $t->text("summary")->nullable();
                $t->text("follow_up")->nullable();
                $t->text("referral")->nullable();
                $t->string("status")->default("open");
                $t->boolean("private")->default(true);
                $audit($t);
            });
        }
    }

    public function down(): void
    {
        foreach ([
            "psychosocial_records","special_needs_records","alumni_records",
            "chaplaincy_records","agriculture_records","drama_records","music_records",
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};