<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("announcements", function (Blueprint $table) {
            $table->id();
            $table->foreignId("school_id")->constrained()->cascadeOnDelete();
            $table->foreignId("staff_id")->nullable()->constrained("staff")->nullOnDelete();
            $table->string("title");
            $table->text("body");
            $table->string("audience")->default("all"); // all, teachers, parents, students
            $table->boolean("is_pinned")->default(false);
            $table->date("publish_date");
            $table->date("expiry_date")->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("announcements"); }
};