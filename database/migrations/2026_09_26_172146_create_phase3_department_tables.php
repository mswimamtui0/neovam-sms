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

        // ---------- HUMAN RESOURCES ----------
        if (!Schema::hasTable("hr_records")) {
            Schema::create("hr_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("leave");
                $t->string("staff_name")->nullable();
                $t->string("staff_no")->nullable();
                $t->string("subject")->nullable();
                $t->text("description")->nullable();
                $t->date("start_date")->nullable();
                $t->date("end_date")->nullable();
                $t->text("resolution")->nullable();
                $t->string("status")->default("open");
                $audit($t);
            });
        }

        // ---------- PROCUREMENT ----------
        if (!Schema::hasTable("procurement_records")) {
            Schema::create("procurement_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("order");
                $t->string("item_name")->nullable();
                $t->string("supplier")->nullable();
                $t->integer("quantity")->default(0);
                $t->string("unit")->nullable();
                $t->decimal("unit_price", 14, 2)->default(0);
                $t->decimal("total_amount", 14, 2)->default(0);
                $t->string("currency")->default("TZS");
                $t->date("expected_on")->nullable();
                $t->date("received_on")->nullable();
                $t->text("notes")->nullable();
                $t->string("status")->default("pending");
                $audit($t);
            });
        }

        // ---------- RECORDS & ARCHIVES ----------
        if (!Schema::hasTable("records_entries")) {
            Schema::create("records_entries", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("student");
                $t->string("subject_name")->nullable();
                $t->string("reference_no")->nullable();
                $t->string("document_type")->nullable();
                $t->text("notes")->nullable();
                $t->date("issued_on")->nullable();
                $t->date("valid_until")->nullable();
                $t->string("status")->default("active");
                $audit($t);
            });
        }

        // ---------- UNIFORM / TAILORING ----------
        if (!Schema::hasTable("uniform_records")) {
            Schema::create("uniform_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("order");
                $t->string("student_name")->nullable();
                $t->string("class_name")->nullable();
                $t->string("item")->nullable();
                $t->string("size")->nullable();
                $t->integer("quantity")->default(1);
                $t->decimal("amount", 14, 2)->default(0);
                $t->date("ready_on")->nullable();
                $t->date("delivered_on")->nullable();
                $t->text("notes")->nullable();
                $t->string("status")->default("pending");
                $audit($t);
            });
        }

        // ---------- LAUNDRY ----------
        if (!Schema::hasTable("laundry_records")) {
            Schema::create("laundry_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("linen");
                $t->string("dorm_name")->nullable();
                $t->string("item")->nullable();
                $t->integer("quantity")->default(0);
                $t->date("received_on")->nullable();
                $t->date("returned_on")->nullable();
                $t->text("notes")->nullable();
                $t->string("status")->default("pending");
                $audit($t);
            });
        }
    }

    public function down(): void
    {
        foreach ([
            "laundry_records","uniform_records","records_entries",
            "procurement_records","hr_records",
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};