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

        // ---------- TRANSPORT ----------
        if (!Schema::hasTable("transport_trips")) {
            Schema::create("transport_trips", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("bus_code")->nullable();
                $t->string("route_name")->nullable();
                $t->string("driver_name")->nullable();
                $t->string("conductor_name")->nullable();
                $t->integer("student_count")->default(0);
                $t->date("trip_date")->nullable();
                $t->string("trip_time")->nullable();
                $t->string("direction")->default("to_school");
                $t->decimal("fuel_used", 10, 2)->default(0);
                $t->text("notes")->nullable();
                $t->string("status")->default("scheduled");
                $audit($t);
            });
        }

        // ---------- STORES ----------
        if (!Schema::hasTable("store_records")) {
            Schema::create("store_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("item_name")->nullable();
                $t->string("item_code")->nullable();
                $t->string("type")->default("in");
                $t->integer("quantity")->default(0);
                $t->string("unit")->nullable();
                $t->string("issued_to")->nullable();
                $t->string("supplier")->nullable();
                $t->string("reference_no")->nullable();
                $t->text("notes")->nullable();
                $t->string("status")->default("completed");
                $audit($t);
            });
        }

        // ---------- MAINTENANCE ----------
        if (!Schema::hasTable("maintenance_records")) {
            Schema::create("maintenance_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("location")->nullable();
                $t->string("item")->nullable();
                $t->string("issue")->nullable();
                $t->text("description")->nullable();
                $t->string("assigned_to")->nullable();
                $t->string("priority")->default("normal");
                $t->date("completed_on")->nullable();
                $t->text("parts_used")->nullable();
                $t->decimal("cost", 14, 2)->default(0);
                $t->string("status")->default("open");
                $audit($t);
            });
        }

        // ---------- FRONT OFFICE ----------
        if (!Schema::hasTable("front_office_records")) {
            Schema::create("front_office_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("visitor");
                $t->string("visitor_name")->nullable();
                $t->string("visitor_phone")->nullable();
                $t->string("host_name")->nullable();
                $t->string("purpose")->nullable();
                $t->text("message")->nullable();
                $t->date("visit_date")->nullable();
                $t->string("visit_time")->nullable();
                $t->string("status")->default("open");
                $audit($t);
            });
        }

        // ---------- ICT ----------
        if (!Schema::hasTable("ict_records")) {
            Schema::create("ict_records", function (Blueprint $t) use ($school, $audit) {
                $t->id();
                $school($t);
                $t->string("type")->default("ticket");
                $t->string("title")->nullable();
                $t->text("description")->nullable();
                $t->string("reported_by")->nullable();
                $t->string("assigned_to")->nullable();
                $t->string("device")->nullable();
                $t->string("priority")->default("normal");
                $t->text("resolution")->nullable();
                $t->string("status")->default("open");
                $audit($t);
            });
        }
    }

    public function down(): void
    {
        foreach ([
            "ict_records","front_office_records","maintenance_records",
            "store_records","transport_trips",
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};