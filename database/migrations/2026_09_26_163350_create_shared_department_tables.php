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

        // ---------- HEALTH ----------
        if (!Schema::hasTable("health_records")) {
            Schema::create("health_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->foreignId("student_id")->nullable()->index();
                $t->string("student_name")->nullable();
                $t->string("class_name")->nullable();
                $t->string("type")->default("visit"); // visit | medication | emergency | injury | referral | allergy
                $t->string("severity")->default("low"); // low | medium | high | critical
                $t->text("symptoms")->nullable();
                $t->text("treatment")->nullable();
                $t->text("notes")->nullable();
                $t->string("status")->default("open"); // open | treated | referred | closed
                $t->boolean("parent_notified")->default(false);
                $audit($t);
            });
        }

        // ---------- DISCIPLINE ----------
        if (!Schema::hasTable("discipline_cases")) {
            Schema::create("discipline_cases", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->foreignId("student_id")->nullable()->index();
                $t->string("student_name")->nullable();
                $t->string("class_name")->nullable();
                $t->string("category")->default("minor"); // minor | major | serious | praise
                $t->string("offence")->nullable();
                $t->text("description")->nullable();
                $t->string("action_taken")->nullable();
                $t->string("status")->default("open"); // open | resolved | escalated | closed
                $t->boolean("parent_notified")->default(false);
                $audit($t);
            });
        }

        // ---------- SPORTS ----------
        if (!Schema::hasTable("sports_records")) {
            Schema::create("sports_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("sport_name")->nullable();
                $t->string("team_name")->nullable();
                $t->string("event_type")->default("training"); // training | match | tournament | injury
                $t->string("opponent")->nullable();
                $t->string("venue")->nullable();
                $t->string("result")->nullable();
                $t->text("notes")->nullable();
                $t->foreignId("student_id")->nullable()->index();
                $audit($t);
            });
        }

        // ---------- DUTY ----------
        if (!Schema::hasTable("duty_logs")) {
            Schema::create("duty_logs", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->date("duty_date")->nullable();
                $t->string("shift")->default("day"); // day | night | morning | evening
                $t->string("duty_type")->default("general");
                $t->text("handover_notes")->nullable();
                $t->text("incidents")->nullable();
                $t->string("status")->default("scheduled"); // scheduled | done | missed
                $audit($t);
            });
        }

        // ---------- BOARDING ----------
        if (!Schema::hasTable("boarding_logs")) {
            Schema::create("boarding_logs", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("dorm_name")->nullable();
                $t->string("type")->default("roll_call"); // roll_call | incident | inspection | maintenance
                $t->text("description")->nullable();
                $t->foreignId("student_id")->nullable()->index();
                $t->string("status")->default("open");
                $audit($t);
            });
        }

        // ---------- FEEDING ----------
        if (!Schema::hasTable("feeding_logs")) {
            Schema::create("feeding_logs", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->date("meal_date")->nullable();
                $t->string("meal_type")->nullable(); // breakfast | lunch | dinner | snack
                $t->text("menu")->nullable();
                $t->integer("served_count")->default(0);
                $t->text("allergy_notes")->nullable();
                $t->text("stock_notes")->nullable();
                $t->string("status")->default("planned");
                $audit($t);
            });
        }

        // ---------- LIBRARY ----------
        if (!Schema::hasTable("library_loans")) {
            Schema::create("library_loans", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("book_title")->nullable();
                $t->string("book_code")->nullable();
                $t->foreignId("student_id")->nullable()->index();
                $t->string("student_name")->nullable();
                $t->date("borrowed_on")->nullable();
                $t->date("due_on")->nullable();
                $t->date("returned_on")->nullable();
                $t->string("status")->default("borrowed"); // borrowed | returned | overdue | lost
                $t->boolean("parent_notified")->default(false);
                $audit($t);
            });
        }

        // ---------- GUIDANCE ----------
        if (!Schema::hasTable("counseling_sessions")) {
            Schema::create("counseling_sessions", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->foreignId("student_id")->nullable()->index();
                $t->string("student_name")->nullable();
                $t->string("session_type")->default("general"); // general | academic | career | personal
                $t->text("summary")->nullable();
                $t->text("follow_up")->nullable();
                $t->string("status")->default("open");
                $t->boolean("private")->default(true);
                $audit($t);
            });
        }

        // ---------- ENVIRONMENT ----------
        if (!Schema::hasTable("environment_logs")) {
            Schema::create("environment_logs", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("area")->nullable();
                $t->string("type")->default("cleaning"); // cleaning | inspection | waste | maintenance
                $t->text("description")->nullable();
                $t->integer("rating")->nullable();
                $t->string("status")->default("open");
                $audit($t);
            });
        }

        // ---------- SECURITY ----------
        if (!Schema::hasTable("security_logs")) {
            Schema::create("security_logs", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("patrol"); // patrol | visitor | incident | gate
                $t->string("visitor_name")->nullable();
                $t->string("visitor_phone")->nullable();
                $t->string("purpose")->nullable();
                $t->text("description")->nullable();
                $t->string("status")->default("open");
                $audit($t);
            });
        }

        // ---------- FINANCE ----------
        if (!Schema::hasTable("finance_records")) {
            Schema::create("finance_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("payment"); // invoice | payment | expense | receipt
                $t->foreignId("student_id")->nullable()->index();
                $t->string("student_name")->nullable();
                $t->decimal("amount", 14, 2)->default(0);
                $t->string("currency")->default("TZS");
                $t->text("description")->nullable();
                $t->string("reference_no")->nullable();
                $t->string("status")->default("pending"); // pending | paid | cancelled
                $t->boolean("parent_notified")->default(false);
                $audit($t);
            });
        }

        // ---------- ADMINISTRATION ----------
        if (!Schema::hasTable("administration_logs")) {
            Schema::create("administration_logs", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("letter"); // letter | meeting | circular | notice | report
                $t->string("title")->nullable();
                $t->text("body")->nullable();
                $t->string("audience")->nullable(); // staff | parents | all | class
                $t->string("status")->default("draft"); // draft | published | archived
                $audit($t);
            });
        }

        // ---------- CROSS-DEPARTMENT FLOW MIRRORS ----------
        if (!Schema::hasTable("department_mirrors")) {
            Schema::create("department_mirrors", function (Blueprint $t) use ($school) {
                $t->id();
                $school($t);
                $t->string("source_table");
                $t->unsignedBigInteger("source_id");
                $t->string("target_department");
                $t->text("summary")->nullable();
                $t->boolean("read_only")->default(true);
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        foreach ([
            "department_mirrors","administration_logs","finance_records",
            "security_logs","environment_logs","counseling_sessions",
            "library_loans","feeding_logs","boarding_logs","duty_logs",
            "sports_records","discipline_cases","health_records",
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};